<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    protected $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        
        // Criar diretório de backups se não existir
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function index()
    {
        $backups = $this->getBackups();
        
        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $backupName = 'backup_' . date('Y-m-d_His');
            $sqlFilename = $backupName . '.sql';
            $zipFilename = $backupName . '.zip';
            $sqlFilepath = $this->backupPath . '/' . $sqlFilename;
            $zipFilepath = $this->backupPath . '/' . $zipFilename;
            
            // Criar arquivo SQL de backup
            $handle = fopen($sqlFilepath, 'w');
            
            if (!$handle) {
                throw new \Exception('Não foi possível criar o arquivo de backup.');
            }
            
            // Escrever cabeçalho do backup
            fwrite($handle, "-- Backup do Banco de Dados\n");
            fwrite($handle, "-- Data: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Banco: " . config('database.connections.mysql.database') . "\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET AUTOCOMMIT = 0;\n");
            fwrite($handle, "START TRANSACTION;\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n\n");
            
            // Obter todas as tabelas
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableKey = 'Tables_in_' . $dbName;
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Exportar estrutura da tabela
                fwrite($handle, "-- Estrutura da tabela `{$tableName}`\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = $createTable[0]->{'Create Table'};
                fwrite($handle, $createTableSql . ";\n\n");
                
                // Exportar dados da tabela
                $rows = DB::table($tableName)->get();
                
                if ($rows->count() > 0) {
                    fwrite($handle, "-- Dados da tabela `{$tableName}`\n");
                    
                    // Preparar INSERT statements em lotes
                    $chunkSize = 100;
                    $rows->chunk($chunkSize)->each(function ($chunk) use ($handle, $tableName) {
                        $columns = array_keys((array) $chunk->first());
                        $columnList = '`' . implode('`, `', $columns) . '`';
                        
                        fwrite($handle, "INSERT INTO `{$tableName}` ({$columnList}) VALUES\n");
                        
                        $values = [];
                        foreach ($chunk as $row) {
                            $rowArray = (array) $row;
                            $rowValues = [];
                            
                            foreach ($rowArray as $value) {
                                if ($value === null) {
                                    $rowValues[] = 'NULL';
                                } else {
                                    $rowValues[] = "'" . addslashes($value) . "'";
                                }
                            }
                            
                            $values[] = '(' . implode(', ', $rowValues) . ')';
                        }
                        
                        fwrite($handle, implode(",\n", $values) . ";\n\n");
                    });
                }
            }
            
            // Finalizar backup SQL
            fwrite($handle, "COMMIT;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            
            fclose($handle);
            
            // Verificar se o arquivo SQL foi criado
            if (!file_exists($sqlFilepath) || filesize($sqlFilepath) == 0) {
                throw new \Exception('Backup SQL criado mas está vazio ou não foi criado.');
            }
            
            // Criar arquivo ZIP com banco de dados e arquivos de upload
            $zip = new \ZipArchive();
            if ($zip->open($zipFilepath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Não foi possível criar o arquivo ZIP.');
            }
            
            // Adicionar arquivo SQL ao ZIP
            $zip->addFile($sqlFilepath, 'database.sql');
            
            // Adicionar pasta de uploads (storage/app/public)
            $uploadsPath = storage_path('app/public');
            if (file_exists($uploadsPath)) {
                $this->addDirectoryToZip($zip, $uploadsPath, 'uploads');
            }
            
            $zip->close();
            
            // Remover arquivo SQL temporário (já está no ZIP)
            if (file_exists($sqlFilepath)) {
                unlink($sqlFilepath);
            }
            
            // Verificar se o arquivo ZIP foi criado
            if (!file_exists($zipFilepath) || filesize($zipFilepath) == 0) {
                throw new \Exception('Backup ZIP criado mas está vazio ou não foi criado.');
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup completo criado com sucesso! (Banco de dados + Arquivos de upload)');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erro ao criar backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filepath = $this->backupPath . '/' . basename($filename);
        
        if (!file_exists($filepath)) {
            abort(404, 'Backup não encontrado');
        }
        
        return response()->download($filepath);
    }

    protected function addDirectoryToZip($zip, $dir, $zipPath = '')
    {
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            
            $filePath = $dir . '/' . $file;
            $zipFilePath = $zipPath ? $zipPath . '/' . $file : $file;
            
            if (is_dir($filePath)) {
                $zip->addEmptyDir($zipFilePath);
                $this->addDirectoryToZip($zip, $filePath, $zipFilePath);
            } else {
                $zip->addFile($filePath, $zipFilePath);
            }
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt,zip|max:102400', // 100MB max
        ]);
        
        try {
            $file = $request->file('backup_file');
            $originalName = $file->getClientOriginalName();
            
            // Gerar nome único se já existir
            $filename = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension() ?: 'sql';
            $finalFilename = $filename . '_' . date('Y-m-d_His') . '.' . $extension;
            $filepath = $this->backupPath . '/' . $finalFilename;
            
            // Mover arquivo para o diretório de backups
            $file->move($this->backupPath, $finalFilename);
            
            // Verificar se o arquivo foi movido corretamente
            if (!file_exists($filepath)) {
                throw new \Exception('Erro ao fazer upload do arquivo.');
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup enviado com sucesso! Você pode restaurá-lo agora.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erro ao fazer upload do backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string',
        ]);
        
        try {
            $filename = basename($request->backup_file);
            $filepath = $this->backupPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Arquivo de backup não encontrado.');
            }
            
            $isZip = pathinfo($filepath, PATHINFO_EXTENSION) === 'zip';
            $sqlContent = null;
            $tempDir = null;
            
            if ($isZip) {
                // Extrair arquivo ZIP
                $tempDir = $this->backupPath . '/temp_' . uniqid();
                mkdir($tempDir, 0755, true);
                
                $zip = new \ZipArchive();
                if ($zip->open($filepath) !== TRUE) {
                    throw new \Exception('Não foi possível abrir o arquivo ZIP.');
                }
                
                $zip->extractTo($tempDir);
                $zip->close();
                
                // Procurar arquivo SQL no ZIP
                $sqlFile = $tempDir . '/database.sql';
                if (!file_exists($sqlFile)) {
                    // Tentar encontrar qualquer arquivo .sql
                    $files = glob($tempDir . '/*.sql');
                    if (empty($files)) {
                        throw new \Exception('Arquivo SQL não encontrado no backup ZIP.');
                    }
                    $sqlFile = $files[0];
                }
                
                $sqlContent = file_get_contents($sqlFile);
                
                // Restaurar arquivos de upload
                $uploadsDir = $tempDir . '/uploads';
                if (file_exists($uploadsDir)) {
                    $targetUploadsPath = storage_path('app/public');
                    
                    // Criar diretório se não existir
                    if (!file_exists($targetUploadsPath)) {
                        mkdir($targetUploadsPath, 0755, true);
                    }
                    
                    // Copiar arquivos
                    $this->copyDirectory($uploadsDir, $targetUploadsPath);
                }
            } else {
                // Arquivo SQL simples
                $sqlContent = file_get_contents($filepath);
            }
            
            if (empty($sqlContent)) {
                throw new \Exception('Arquivo de backup está vazio.');
            }
            
            // Desabilitar verificação de chaves estrangeiras temporariamente
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Dividir o SQL em comandos individuais
            // Remover comentários e dividir por ponto e vírgula
            $sql = preg_replace('/--.*$/m', '', $sqlContent); // Remove comentários de linha
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove comentários de bloco
            
            // Dividir em comandos
            $commands = array_filter(
                array_map('trim', explode(';', $sql)),
                function($cmd) {
                    return !empty($cmd) && !preg_match('/^(SET|START|COMMIT)/i', trim($cmd));
                }
            );
            
            // Executar cada comando
            foreach ($commands as $command) {
                $command = trim($command);
                if (!empty($command)) {
                    try {
                        DB::unprepared($command);
                    } catch (\Exception $e) {
                        // Ignorar erros de tabelas que não existem ainda
                        if (strpos($e->getMessage(), 'doesn\'t exist') === false) {
                            throw $e;
                        }
                    }
                }
            }
            
            // Reabilitar verificação de chaves estrangeiras
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            // Limpar diretório temporário
            if ($tempDir && file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup restaurado com sucesso! (Banco de dados + Arquivos de upload)');
                
        } catch (\Exception $e) {
            // Reabilitar verificação de chaves estrangeiras em caso de erro
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e2) {
                // Ignorar
            }
            
            // Limpar diretório temporário em caso de erro
            if (isset($tempDir) && $tempDir && file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erro ao restaurar backup: ' . $e->getMessage());
        }
    }

    protected function copyDirectory($source, $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $files = scandir($source);
        
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            
            $sourcePath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;
            
            if (is_dir($sourcePath)) {
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
    }

    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $filePath = $dir . '/' . $file;
            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }
        
        rmdir($dir);
    }

    public function destroy($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . basename($filename);
            
            if (!file_exists($filepath)) {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Backup não encontrado.');
            }
            
            unlink($filepath);
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup excluído com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erro ao excluir backup: ' . $e->getMessage());
        }
    }

    protected function getBackups()
    {
        $backups = [];
        // Buscar arquivos .sql e .zip
        $sqlFiles = glob($this->backupPath . '/*.sql');
        $zipFiles = glob($this->backupPath . '/*.zip');
        $files = array_merge($sqlFiles, $zipFiles);
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => filesize($file),
                'created_at' => filemtime($file),
                'path' => $file,
                'type' => pathinfo($file, PATHINFO_EXTENSION) === 'zip' ? 'zip' : 'sql',
            ];
        }
        
        // Ordenar por data (mais recente primeiro)
        usort($backups, function($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });
        
        return $backups;
    }
}


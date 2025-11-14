<?php

namespace App\Console\Commands;

use App\Models\EstadoMunicipio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SeedEstadosMunicipios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:estados-municipios {--force : Força a execução mesmo se já houver dados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popula a tabela estados_municipios com todos os estados e municípios do Brasil';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && EstadoMunicipio::count() > 0) {
            $this->warn('A tabela já possui dados. Use --force para recriar.');
            return 1;
        }

        if ($this->option('force')) {
            $this->info('Limpando dados existentes...');
            DB::table('estados_municipios')->truncate();
        }

        $this->info('Lendo arquivo JSON...');
        
        $jsonPath = database_path('migrations/json/estados_municipios');
        
        if (!File::exists($jsonPath)) {
            $this->error("Arquivo não encontrado: {$jsonPath}");
            return 1;
        }

        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return 1;
        }

        if (!isset($data['paises'][0]['estados'])) {
            $this->error('Estrutura do JSON inválida. Esperado: paises[0].estados');
            return 1;
        }

        $estados = $data['paises'][0]['estados'];
        $totalEstados = count($estados);
        $totalMunicipios = 0;

        $this->info("Processando {$totalEstados} estados...");

        $bar = $this->output->createProgressBar($totalEstados);
        $bar->start();

        $inserts = [];
        $batchSize = 500; // Inserir em lotes para melhor performance

        foreach ($estados as $estado) {
            $sigla = $estado['sigla'] ?? '';
            $nome = $estado['nome'] ?? '';
            $cidades = $estado['cidades'] ?? [];

            foreach ($cidades as $cidade) {
                $inserts[] = [
                    'estado' => $sigla,
                    'estado_nome' => $nome,
                    'municipio' => $cidade,
                    'codigo_ibge' => null, // Código IBGE não está no JSON fornecido
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $totalMunicipios++;

                // Inserir em lotes para melhor performance
                if (count($inserts) >= $batchSize) {
                    DB::table('estados_municipios')->insert($inserts);
                    $inserts = [];
                }
            }

            $bar->advance();
        }

        // Inserir registros restantes
        if (!empty($inserts)) {
            DB::table('estados_municipios')->insert($inserts);
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Processados {$totalEstados} estados");
        $this->info("✓ Inseridos {$totalMunicipios} municípios");
        $this->info("✓ População concluída com sucesso!");

        return 0;
    }
}

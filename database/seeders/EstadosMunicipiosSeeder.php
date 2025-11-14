<?php

namespace Database\Seeders;

use App\Models\EstadoMunicipio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EstadosMunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem dados
        if (EstadoMunicipio::count() > 0) {
            $this->command->warn('A tabela estados_municipios já possui dados. Use --force para recriar.');
            return;
        }

        $this->command->info('Lendo arquivo JSON...');
        
        $jsonPath = database_path('migrations/json/estados_municipios');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("Arquivo não encontrado: {$jsonPath}");
            return;
        }

        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }

        if (!isset($data['paises'][0]['estados'])) {
            $this->command->error('Estrutura do JSON inválida. Esperado: paises[0].estados');
            return;
        }

        $estados = $data['paises'][0]['estados'];
        $totalEstados = count($estados);
        $totalMunicipios = 0;

        $this->command->info("Processando {$totalEstados} estados...");

        $bar = $this->command->getOutput()->createProgressBar($totalEstados);
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
        $this->command->newLine();
        $this->command->info("✓ Processados {$totalEstados} estados");
        $this->command->info("✓ Inseridos {$totalMunicipios} municípios");
        $this->command->info("✓ População concluída com sucesso!");
    }
}


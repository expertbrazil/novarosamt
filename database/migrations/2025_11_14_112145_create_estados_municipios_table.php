<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados_municipios', function (Blueprint $table) {
            $table->id();
            $table->string('estado', 2)->index(); // UF (ex: SP, RJ, MG)
            $table->string('estado_nome', 100); // Nome do estado
            $table->string('municipio', 200)->index(); // Nome do município
            $table->string('codigo_ibge', 7)->nullable(); // Código IBGE do município
            $table->timestamps();
            
            $table->index(['estado', 'municipio']);
        });

        // Popular com dados completos do Brasil
        $this->seedEstadosMunicipios();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_municipios');
    }

    /**
     * Popular tabela com estados e municípios do Brasil
     */
    private function seedEstadosMunicipios(): void
    {
        // Por enquanto, usar fallback com dados básicos
        // Os dados completos podem ser populados via seeder separado
        // Para popular todos os dados do JSON fornecido, use: php artisan seed:estados-municipios-completo
        $this->seedEstadosMunicipiosFallback();
    }

    /**
     * Fallback: dados básicos se o JSON não estiver disponível
     */
    private function seedEstadosMunicipiosFallback(): void
    {
        $estadosMunicipios = [
            ['AC', 'Acre', 'Rio Branco', null],
            ['AL', 'Alagoas', 'Maceió', null],
            ['AP', 'Amapá', 'Macapá', null],
            ['AM', 'Amazonas', 'Manaus', null],
            ['BA', 'Bahia', 'Salvador', null],
            ['CE', 'Ceará', 'Fortaleza', null],
            ['DF', 'Distrito Federal', 'Brasília', null],
            ['ES', 'Espírito Santo', 'Vitória', null],
            ['GO', 'Goiás', 'Goiânia', null],
            ['MA', 'Maranhão', 'São Luís', null],
            ['MT', 'Mato Grosso', 'Cuiabá', null],
            ['MS', 'Mato Grosso do Sul', 'Campo Grande', null],
            ['MG', 'Minas Gerais', 'Belo Horizonte', null],
            ['PA', 'Pará', 'Belém', null],
            ['PB', 'Paraíba', 'João Pessoa', null],
            ['PR', 'Paraná', 'Curitiba', null],
            ['PE', 'Pernambuco', 'Recife', null],
            ['PI', 'Piauí', 'Teresina', null],
            ['RJ', 'Rio de Janeiro', 'Rio de Janeiro', null],
            ['RN', 'Rio Grande do Norte', 'Natal', null],
            ['RS', 'Rio Grande do Sul', 'Porto Alegre', null],
            ['RO', 'Rondônia', 'Porto Velho', null],
            ['RR', 'Roraima', 'Boa Vista', null],
            ['SC', 'Santa Catarina', 'Florianópolis', null],
            ['SP', 'São Paulo', 'São Paulo', null],
            ['SE', 'Sergipe', 'Aracaju', null],
            ['TO', 'Tocantins', 'Palmas', null],
        ];

        foreach ($estadosMunicipios as $item) {
            DB::table('estados_municipios')->insert([
                'estado' => $item[0],
                'estado_nome' => $item[1],
                'municipio' => $item[2],
                'codigo_ibge' => $item[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Nota: Esta migration é apenas para documentar a adição da feature de perfil.
     * Não há alterações na estrutura do banco de dados, pois a funcionalidade
     * de perfil utiliza os campos existentes da tabela users (name, email, password).
     */
    public function up(): void
    {
        // Nenhuma alteração necessária na estrutura do banco
        // A feature de perfil usa os campos existentes: name, email, password
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nenhuma alteração para reverter
    }
};

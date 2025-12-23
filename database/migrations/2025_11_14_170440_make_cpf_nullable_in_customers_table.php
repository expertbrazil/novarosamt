<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Tornar CPF nullable para permitir PJ sem CPF
            $table->string('cpf', 14)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Reverter para NOT NULL (pode causar erro se houver registros com NULL)
            $table->string('cpf', 14)->nullable(false)->change();
        });
    }
};

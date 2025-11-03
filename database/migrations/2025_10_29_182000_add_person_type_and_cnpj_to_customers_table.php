<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('person_type', 2)->default('PF')->after('name'); // PF or PJ
            $table->string('cnpj', 18)->nullable()->unique()->after('cpf');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['cnpj']);
            $table->dropColumn(['person_type', 'cnpj']);
        });
    }
};






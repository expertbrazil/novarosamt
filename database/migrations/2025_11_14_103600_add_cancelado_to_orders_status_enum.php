<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Adicionar 'cancelado' ao enum do status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendente', 'aguardando_pagamento', 'aprovado', 'entregue', 'cancelado') DEFAULT 'pendente'");
    }

    public function down(): void
    {
        // Remover 'cancelado' do enum (converter cancelados para pendente)
        DB::statement("UPDATE orders SET status = 'pendente' WHERE status = 'cancelado'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendente', 'aguardando_pagamento', 'aprovado', 'entregue') DEFAULT 'pendente'");
    }
};

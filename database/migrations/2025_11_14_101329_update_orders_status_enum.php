<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Atualizar valores existentes para os novos status
        DB::statement("UPDATE orders SET status = 'pendente' WHERE status = 'pending'");
        DB::statement("UPDATE orders SET status = 'aprovado' WHERE status = 'processing'");
        DB::statement("UPDATE orders SET status = 'entregue' WHERE status = 'completed'");
        DB::statement("UPDATE orders SET status = 'pendente' WHERE status = 'cancelled'"); // Cancelled volta para pendente
        
        // Alterar o enum do status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendente', 'aguardando_pagamento', 'aprovado', 'entregue') DEFAULT 'pendente'");
    }

    public function down(): void
    {
        // Reverter para os valores antigos
        DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'pendente'");
        DB::statement("UPDATE orders SET status = 'processing' WHERE status = 'aguardando_pagamento'");
        DB::statement("UPDATE orders SET status = 'processing' WHERE status = 'aprovado'");
        DB::statement("UPDATE orders SET status = 'completed' WHERE status = 'entregue'");
        
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};

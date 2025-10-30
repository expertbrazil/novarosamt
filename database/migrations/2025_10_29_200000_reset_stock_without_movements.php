<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Zera o estoque de produtos que ainda não possuem movimentações
        DB::statement('
            UPDATE products p
            LEFT JOIN (
                SELECT DISTINCT product_id FROM stock_movements
            ) sm ON sm.product_id = p.id
            SET p.stock = 0
            WHERE sm.product_id IS NULL
        ');
    }

    public function down(): void
    {
        // Sem reversão segura
    }
};



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('profit_margin_percent', 5, 2)->default(0)->after('price');
            $table->decimal('sale_price', 10, 2)->nullable()->after('profit_margin_percent');
        });

        // Inicializa sale_price como price (margem 0)
        DB::table('products')->update(['sale_price' => DB::raw('price')]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['profit_margin_percent', 'sale_price']);
        });
    }
};




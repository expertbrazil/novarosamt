<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductPricesFromStock extends Command
{
    protected $signature = 'products:update-prices-from-stock {--dry-run : Apenas mostra o que seria alterado}';

    protected $description = 'Atualiza preços de compra e venda dos produtos baseado nas últimas entradas de estoque';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $this->info('Atualizando preços de compra e venda a partir das entradas de estoque...');
        $this->newLine();

        $products = Product::query()->orderBy('id')->get();
        $updated = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            // Buscar a última entrada de estoque com unit_cost
            $lastEntry = StockMovement::where('product_id', $product->id)
                ->whereIn('type', ['in', 'adjustment_in'])
                ->whereNotNull('unit_cost')
                ->orderByDesc('moved_at')
                ->orderByDesc('id')
                ->first();

            if (!$lastEntry || !$lastEntry->unit_cost) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $newPrice = (float) $lastEntry->unit_cost;
            $margin = (float) ($product->profit_margin_percent ?? 0);
            $newSalePrice = round($newPrice * (1 + ($margin / 100)), 2);

            if ($dry) {
                $this->line("\n#{$product->id} {$product->name}");
                $this->line("  Preço atual: R$ " . number_format($product->price, 2, ',', '.'));
                $this->line("  Novo preço: R$ " . number_format($newPrice, 2, ',', '.'));
                $this->line("  Margem: {$margin}%");
                $this->line("  Preço de venda atual: R$ " . number_format($product->sale_price ?? 0, 2, ',', '.'));
                $this->line("  Novo preço de venda: R$ " . number_format($newSalePrice, 2, ',', '.'));
            } else {
                DB::transaction(function () use ($product, $newPrice, $newSalePrice) {
                    $product->price = $newPrice;
                    $product->sale_price = $newSalePrice;
                    $product->save();
                });
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dry) {
            $this->info("Dry-run concluído. {$updated} produtos seriam atualizados, {$skipped} seriam ignorados.");
        } else {
            $this->info("Atualização concluída! {$updated} produtos atualizados, {$skipped} ignorados (sem entradas de estoque).");
        }

        return self::SUCCESS;
    }
}


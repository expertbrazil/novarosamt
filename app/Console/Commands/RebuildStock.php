<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Console\Command;

class RebuildStock extends Command
{
    protected $signature = 'stock:rebuild {--dry-run : Apenas mostra o que seria alterado}';

    protected $description = 'Recalcula o estoque e o último custo dos produtos a partir das movimentações';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $this->info('Recalculando estoque e último custo a partir de stock_movements...');

        $products = Product::query()->orderBy('id')->get();

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $inQty = (int) round(StockMovement::where('product_id', $product->id)
                ->whereIn('type', ['in','adjustment_in'])
                ->sum('quantity'));
            $outQty = (int) round(StockMovement::where('product_id', $product->id)
                ->whereIn('type', ['out','adjustment_out'])
                ->sum('quantity'));

            $stock = max(0, $inQty - $outQty);

            $lastIn = StockMovement::where('product_id', $product->id)
                ->whereIn('type', ['in','adjustment_in'])
                ->orderByDesc('moved_at')
                ->orderByDesc('id')
                ->first();

            if ($dry) {
                $this->line("\n#{$product->id} {$product->name} -> stock={$stock}; last_cost=" . ($lastIn?->unit_cost ?? 'null'));
            } else {
                $product->stock = $stock;
                $product->last_purchase_cost = $lastIn?->unit_cost;
                $product->last_purchase_at = $lastIn?->moved_at;
                $product->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info($dry ? 'Dry-run concluído.' : 'Rebuild concluído com sucesso.');

        return self::SUCCESS;
    }
}



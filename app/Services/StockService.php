<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    public function registerEntry(Product $product, float $quantity, float $unitCost, array $meta = []): StockMovement
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantidade deve ser maior que zero.');
        }

        return DB::transaction(function () use ($product, $quantity, $unitCost, $meta) {
            $movement = new StockMovement([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'reason' => $meta['reason'] ?? null,
                'moved_at' => $meta['moved_at'] ?? now(),
                'user_id' => $meta['user_id'] ?? Auth::id(),
            ]);

            if (!empty($meta['reference'])) {
                $movement->reference()->associate($meta['reference']);
            }

            $movement->save();

            $product->increment('stock', (int) round($quantity));
            $product->last_purchase_cost = $unitCost;
            $product->last_purchase_at = $movement->moved_at;
            
            // Atualizar o preço de compra com o custo unitário da entrada
            $product->price = $unitCost;
            
            // Recalcular o preço de venda baseado no novo preço de compra e margem de lucro
            $margin = (float) ($product->profit_margin_percent ?? 0);
            $product->sale_price = round($unitCost * (1 + ($margin / 100)), 2);
            
            $product->save();

            return $movement;
        });
    }

    public function registerExit(Product $product, float $quantity, array $meta = []): StockMovement
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantidade deve ser maior que zero.');
        }

        return DB::transaction(function () use ($product, $quantity, $meta) {
            $intQty = (int) round($quantity);

            // Bloquear saldo negativo
            if ($product->stock < $intQty) {
                throw new InvalidArgumentException("Estoque insuficiente para o produto: {$product->name}");
            }

            // Idempotência para OrderItem
            if (!empty($meta['reference']) && $meta['reference'] instanceof OrderItem) {
                $existing = StockMovement::where('product_id', $product->id)
                    ->where('type', 'out')
                    ->where('reference_type', $meta['reference']::class)
                    ->where('reference_id', $meta['reference']->id)
                    ->first();
                if ($existing) {
                    return $existing;
                }
            }

            $movement = new StockMovement([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $quantity,
                'unit_cost' => null,
                'reason' => $meta['reason'] ?? null,
                'moved_at' => $meta['moved_at'] ?? now(),
                'user_id' => $meta['user_id'] ?? Auth::id(),
            ]);

            if (!empty($meta['reference'])) {
                $movement->reference()->associate($meta['reference']);
            }

            $movement->save();

            $product->decrement('stock', $intQty);

            return $movement;
        });
    }

    public function adjust(Product $product, float $quantity, string $direction, ?float $unitCost = null, array $meta = []): StockMovement
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantidade deve ser maior que zero.');
        }

        $direction = strtolower($direction);

        return DB::transaction(function () use ($product, $quantity, $direction, $unitCost, $meta) {
            if ($direction === 'in') {
                if ($unitCost === null) {
                    throw new InvalidArgumentException('Custo unitário é obrigatório para ajuste de entrada.');
                }
                $movement = new StockMovement([
                    'product_id' => $product->id,
                    'type' => 'adjustment_in',
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'reason' => $meta['reason'] ?? 'Ajuste de entrada',
                    'moved_at' => $meta['moved_at'] ?? now(),
                    'user_id' => $meta['user_id'] ?? Auth::id(),
                ]);
                $movement->save();
                $product->increment('stock', (int) round($quantity));
                // Não atualiza last_purchase_* em ajuste, a menos que explicitado
                return $movement;
            }

            if ($direction === 'out') {
                $intQty = (int) round($quantity);
                if ($product->stock < $intQty) {
                    throw new InvalidArgumentException("Estoque insuficiente para o produto: {$product->name}");
                }
                $movement = new StockMovement([
                    'product_id' => $product->id,
                    'type' => 'adjustment_out',
                    'quantity' => $quantity,
                    'unit_cost' => null,
                    'reason' => $meta['reason'] ?? 'Ajuste de saída',
                    'moved_at' => $meta['moved_at'] ?? now(),
                    'user_id' => $meta['user_id'] ?? Auth::id(),
                ]);
                $movement->save();
                $product->decrement('stock', $intQty);
                return $movement;
            }

            throw new InvalidArgumentException('Direção inválida para ajuste. Use "in" ou "out".');
        });
    }
}



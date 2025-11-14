<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected WhatsAppService $whatsappService,
        protected StockService $stockService
    ) {
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $customer = null;
            
            // If a customer is referenced, get the customer first
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
            } else {
                // Create or update customer from form data
                $personType = $data['customer_person_type'] ?? 'PF';
                $cep = preg_replace('/\D/', '', $data['customer_cep'] ?? '');
                
                $customerData = [
                    'person_type' => $personType,
                    'name' => $data['customer_name'] ?? '',
                    'email' => $data['customer_email'] ?? '',
                    'phone' => preg_replace('/\D/', '', $data['customer_phone'] ?? ''),
                    'cep' => $cep,
                    'street' => $data['customer_street'] ?? null,
                    'number' => $data['customer_number'] ?? null,
                    'complement' => $data['customer_complement'] ?? null,
                    'district' => $data['customer_district'] ?? null,
                    'city' => $data['customer_city'] ?? null,
                    'state' => strtoupper($data['customer_state'] ?? ''),
                    'is_active' => true,
                ];
                
                if ($personType === 'PF') {
                    $cpf = preg_replace('/\D/', '', $data['customer_cpf'] ?? '');
                    $customer = Customer::updateOrCreate(
                        ['cpf' => $cpf],
                        array_merge($customerData, ['cpf' => $cpf, 'birth_date' => $data['customer_birth_date'] ?? null])
                    );
                } else {
                    $cnpj = preg_replace('/\D/', '', $data['customer_cnpj'] ?? '');
                    $customer = Customer::updateOrCreate(
                        ['cnpj' => $cnpj],
                        array_merge($customerData, ['cnpj' => $cnpj])
                    );
                }
            }
            
            // Set order customer fields
            $data['customer_name'] = $customer->name;
            $personType = $customer->person_type ?? ($data['customer_person_type'] ?? 'PF');
            if ($personType === 'PF') {
                $data['customer_cpf'] = $customer->cpf ?? '';
            } else {
                $data['customer_cpf'] = $customer->cnpj ?? ''; // Usa CNPJ no campo customer_cpf para compatibilidade
            }
            $data['customer_email'] = $customer->email ?? '';
            $data['customer_phone'] = $customer->phone ?? '';
            
            // Build address string from components
            $addressParts = array_filter([
                $customer->street,
                $customer->number,
                $customer->complement,
                $customer->district,
                $customer->city,
                $customer->state,
                $customer->cep ? 'CEP: ' . $customer->cep : null
            ]);
            $data['customer_address'] = implode(', ', $addressParts);
            $items = $data['items'];
            unset($data['items']);

            $total = 0;
            $orderItems = [];

            foreach ($items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Não verificar estoque na criação - será verificado apenas quando status for "entregue"
                // A validação de estoque será feita no momento da entrega

                $unitPrice = $product->sale_price ?? $product->price;
                $subtotal = $unitPrice * $itemData['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            $data['total'] = $total;
            $data['status'] = $data['status'] ?? 'pendente'; // Status padrão: Pendente
            $order = Order::create($data);

            foreach ($orderItems as $orderItem) {
                $createdItem = $order->items()->create($orderItem);
                // NÃO dar baixa no estoque aqui - será dado apenas quando status for "entregue"
            }

            // Enviar via WhatsApp
            $this->whatsappService->sendOrder($order);

            return $order->load('items.product');
        });
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        $oldStatus = $order->status;
        $order->update(['status' => $status]);
        
        // Dar baixa no estoque apenas quando mudar para "entregue"
        if ($status === 'entregue' && $oldStatus !== 'entregue') {
            $order->load('items.product');
            foreach ($order->items as $item) {
                // Verificar estoque antes de dar baixa
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Estoque insuficiente para o produto: {$item->product->name}. Estoque disponível: {$item->product->stock}, necessário: {$item->quantity}");
                }
                
                // Verificar se já foi dado baixa (evitar duplicação)
                $hasStockExit = \App\Models\StockMovement::where('product_id', $item->product_id)
                    ->where('type', 'out')
                    ->where('reference_type', \App\Models\OrderItem::class)
                    ->where('reference_id', $item->id)
                    ->exists();
                
                if (!$hasStockExit) {
                    $this->stockService->registerExit(
                        $item->product,
                        (float) $item->quantity,
                        ['reference' => $item]
                    );
                }
            }
        }
        
        // Se cancelar um pedido, reverter qualquer baixa de estoque relacionada
        if ($status === 'cancelado') {
            $order->load('items.product');
            foreach ($order->items as $item) {
                // Buscar movimentações de saída relacionadas a este item
                $stockMovements = \App\Models\StockMovement::where('product_id', $item->product_id)
                    ->where('type', 'out')
                    ->where('reference_type', \App\Models\OrderItem::class)
                    ->where('reference_id', $item->id)
                    ->get();
                
                // Verificar se já foi revertido (evitar duplicação)
                $hasReversal = \App\Models\StockMovement::where('product_id', $item->product_id)
                    ->where('type', 'in')
                    ->where('reference_type', \App\Models\OrderItem::class)
                    ->where('reference_id', $item->id)
                    ->where('reason', 'Cancelamento de pedido')
                    ->exists();
                
                if (!$hasReversal && $stockMovements->isNotEmpty()) {
                    foreach ($stockMovements as $movement) {
                        // Registrar entrada para reverter a saída usando o preço do item
                        $unitCost = $item->price ?? ($item->product->last_purchase_cost ?? 0);
                        $this->stockService->registerEntry(
                            $item->product,
                            (float) $movement->quantity,
                            (float) $unitCost,
                            ['reference' => $item, 'reason' => 'Cancelamento de pedido']
                        );
                    }
                }
            }
        }
        
        return true;
    }

    public function reverseStockForCancelledOrder(Order $order): bool
    {
        if ($order->status !== 'cancelado') {
            throw new \Exception('Apenas pedidos cancelados podem ter o estoque revertido.');
        }

        $order->load('items.product');
        $reversed = false;

        foreach ($order->items as $item) {
            // Buscar movimentações de saída relacionadas a este item
            $stockMovements = \App\Models\StockMovement::where('product_id', $item->product_id)
                ->where('type', 'out')
                ->where('reference_type', \App\Models\OrderItem::class)
                ->where('reference_id', $item->id)
                ->get();
            
            // Verificar se já foi revertido (evitar duplicação)
            $hasReversal = \App\Models\StockMovement::where('product_id', $item->product_id)
                ->where('type', 'in')
                ->where('reference_type', \App\Models\OrderItem::class)
                ->where('reference_id', $item->id)
                ->where('reason', 'Cancelamento de pedido')
                ->exists();
            
            if (!$hasReversal && $stockMovements->isNotEmpty()) {
                foreach ($stockMovements as $movement) {
                    // Registrar entrada para reverter a saída usando o preço do item
                    $unitCost = $item->price ?? ($item->product->last_purchase_cost ?? 0);
                    $this->stockService->registerEntry(
                        $item->product,
                        (float) $movement->quantity,
                        (float) $unitCost,
                        ['reference' => $item, 'reason' => 'Cancelamento de pedido']
                    );
                    $reversed = true;
                }
            }
        }

        return $reversed;
    }
}


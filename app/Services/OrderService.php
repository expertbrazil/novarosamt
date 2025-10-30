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
            // If a customer is referenced, hydrate order customer fields from Customer record
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
                $data['customer_name'] = $customer->name;
                $data['customer_cpf'] = preg_replace('/\D/', '', (string) $customer->cpf);
                $data['customer_email'] = $customer->email ?? '';
                $data['customer_phone'] = $customer->phone ?? '';
                $data['customer_address'] = $customer->address ?? '';
            }
            $items = $data['items'];
            unset($data['items']);

            $total = 0;
            $orderItems = [];

            foreach ($items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto: {$product->name}");
                }

                $unitPrice = $product->last_purchase_cost ?? ($product->sale_price ?? $product->price);
                $subtotal = $unitPrice * $itemData['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];

                // Baixa de estoque por item (idempotente por OrderItem)
            }

            $data['total'] = $total;
            $order = Order::create($data);

            foreach ($orderItems as $orderItem) {
                $createdItem = $order->items()->create($orderItem);
                // Registrar saída vinculada ao OrderItem
                $this->stockService->registerExit(
                    Product::find($createdItem->product_id),
                    (float) $createdItem->quantity,
                    ['reference' => $createdItem]
                );
            }

            // Enviar via WhatsApp
            $this->whatsappService->sendOrder($order);

            return $order->load('items.product');
        });
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        $order->update(['status' => $status]);
        return true;
    }
}


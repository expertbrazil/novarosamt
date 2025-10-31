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
            // Create or update customer
            $personType = $data['customer_person_type'] ?? 'PF';
            $cep = preg_replace('/\D/', '', $data['customer_cep'] ?? '');
            
            $customerData = [
                'person_type' => $personType,
                'name' => $data['customer_name'],
                'email' => $data['customer_email'],
                'phone' => preg_replace('/\D/', '', $data['customer_phone']),
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

            // If a customer is referenced, hydrate order customer fields from Customer record
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
            }
            
            // Set order customer fields
            $data['customer_name'] = $customer->name;
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


<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected WhatsAppService $whatsappService,
        protected StockService $stockService
    ) {
    }

    public function calculateDiscount(float $subtotal, ?string $type, ?float $value): array
    {
        $type = $type ?: null;
        $value = $value !== null ? (float) $value : 0;
        $value = max(0, $value);

        $discountAmount = 0;

        if ($type === 'percent') {
            $value = min($value, 100);
            $discountAmount = round($subtotal * ($value / 100), 2);
        } elseif ($type === 'value') {
            $discountAmount = min($value, $subtotal);
        } else {
            $type = null;
            $value = 0;
        }

        $total = max(0, round($subtotal - $discountAmount, 2));

        return [
            'type' => $type,
            'value' => $value,
            'amount' => $discountAmount,
            'total' => $total,
        ];
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
            $data['customer_id'] = $customer->id;
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

            $discountData = $this->calculateDiscount(
                $total,
                $data['discount_type'] ?? null,
                $data['discount_value'] ?? null
            );

            $data['discount_type'] = $discountData['type'];
            $data['discount_value'] = $discountData['value'];
            $data['discount_amount'] = $discountData['amount'];
            $data['total'] = $discountData['total'];
            $data['status'] = $data['status'] ?? 'pendente'; // Status padrão: Pendente
            $order = Order::create($data);

            foreach ($orderItems as $orderItem) {
                $createdItem = $order->items()->create($orderItem);
                // NÃO dar baixa no estoque aqui - será dado apenas quando status for "entregue"
            }

            // Enviar notificações via WhatsApp após criar pedido (apenas para pedidos do site público)
            // Verifica se é uma rota do site público ou se não tem customer_id (novo cliente)
            $currentRoute = request()->route() ? request()->route()->getName() : 'unknown';
            $isPublicOrder = request()->routeIs('order.store') || 
                           (empty($data['customer_id']) && !request()->routeIs('admin.orders.store'));
            
            Log::info('Verificando se deve enviar notificações', [
                'order_id' => $order->id,
                'current_route' => $currentRoute,
                'is_public_order' => $isPublicOrder,
                'has_customer_id' => !empty($data['customer_id']),
                'route_is_order_store' => request()->routeIs('order.store')
            ]);
            
            if ($isPublicOrder) {
                Log::info('Enviando notificações WhatsApp para pedido do site público', ['order_id' => $order->id]);
                try {
                    $this->sendOrderNotifications($order);
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar notificações WhatsApp após criar pedido', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::info('Notificações não serão enviadas (pedido do admin)', ['order_id' => $order->id]);
            }

            return $order->load('items.product');
        });
    }

    public function updateOrderStatus(Order $order, string $status, ?string $deliveredAt = null): bool
    {
        $oldStatus = $order->status;
        
        $updateData = ['status' => $status];
        
        // Definir data de entrega quando status for "entregue"
        if ($status === 'entregue' && $oldStatus !== 'entregue') {
            $updateData['delivered_at'] = $deliveredAt ? \Carbon\Carbon::parse($deliveredAt) : now();
        }
        
        $order->update($updateData);
        
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

    /**
     * Envia notificações WhatsApp após criar pedido
     * - Envia notificação para o admin
     * - Envia PDF do pedido para o cliente
     */
    protected function sendOrderNotifications(Order $order): void
    {
        try {
            Log::info('Iniciando envio de notificações WhatsApp', ['order_id' => $order->id]);
            
            // Carregar dados do pedido
            $order->load('items.product.category');
            
            // Verificar se Evolution API está configurada
            $evolutionApi = $this->whatsappService->getEvolutionApi();
            if (!$evolutionApi->isConfigured()) {
                Log::warning('Evolution API não está configurada, notificações não serão enviadas', ['order_id' => $order->id]);
                return;
            }
            
            // 1. Enviar notificação para o admin
            $adminPhone = Settings::get('admin_whatsapp_number', '');
            Log::info('=== ENVIANDO PARA ADMIN ===', [
                'order_id' => $order->id,
                'admin_phone' => $adminPhone,
                'admin_phone_formatted' => $adminPhone ?: 'NÃO CONFIGURADO'
            ]);
            
            if (!empty($adminPhone)) {
                try {
                    $adminMessage = $this->formatAdminNotification($order);
                    Log::info('Mensagem do admin preparada', [
                        'order_id' => $order->id,
                        'message_preview' => substr($adminMessage, 0, 100) . '...',
                        'destino' => $adminPhone
                    ]);
                    
                    $result = $evolutionApi->sendTextMessage($adminPhone, $adminMessage);
                    
                    if ($result['success']) {
                        Log::info('✅ Notificação de pedido enviada para ADMIN com sucesso', [
                            'order_id' => $order->id, 
                            'phone' => $adminPhone,
                            'message_type' => 'ADMIN_NOTIFICATION'
                        ]);
                    } else {
                        Log::error('❌ Erro ao enviar notificação para ADMIN', [
                            'order_id' => $order->id,
                            'phone' => $adminPhone,
                            'error' => $result['error'] ?? 'Erro desconhecido'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Exceção ao enviar notificação para ADMIN', [
                        'order_id' => $order->id,
                        'phone' => $adminPhone,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                Log::warning('⚠️ Número do admin não configurado', ['order_id' => $order->id]);
            }
            
            // 2. Enviar mensagem completa para o cliente (com produtos, valor e PIX)
            $customerPhone = $order->customer_phone;
            Log::info('=== ENVIANDO PARA CLIENTE ===', [
                'order_id' => $order->id,
                'customer_phone' => $customerPhone ?: 'não informado',
                'customer_name' => $order->customer_name
            ]);
            
            if (!empty($customerPhone)) {
                try {
                    $customerMessage = $this->formatCustomerMessage($order);
                    Log::info('Mensagem do cliente preparada', [
                        'order_id' => $order->id,
                        'message_preview' => substr($customerMessage, 0, 100) . '...',
                        'destino' => $customerPhone
                    ]);
                    
                    // Enviar mensagem completa com produtos e informações de pagamento
                    $textResult = $evolutionApi->sendTextMessage($customerPhone, $customerMessage);
                    
                    if ($textResult['success']) {
                        Log::info('✅ Mensagem completa enviada para CLIENTE com sucesso', [
                            'order_id' => $order->id,
                            'phone' => $customerPhone,
                            'message_type' => 'CUSTOMER_CONFIRMATION'
                        ]);
                    } else {
                        Log::error('❌ Erro ao enviar mensagem para CLIENTE', [
                            'order_id' => $order->id,
                            'phone' => $customerPhone,
                            'error' => $textResult['error'] ?? 'Erro desconhecido'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Exceção ao enviar notificação para CLIENTE', [
                        'order_id' => $order->id,
                        'phone' => $customerPhone,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::warning('⚠️ Telefone do cliente não informado', ['order_id' => $order->id]);
            }
        } catch (\Exception $e) {
            Log::error('Erro geral ao enviar notificações WhatsApp do pedido', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Não lançar exceção para não interromper a criação do pedido
        }
    }

    /**
     * Formata mensagem simples de notificação para o admin
     */
    protected function formatAdminNotification(Order $order): string
    {
        $totalFormatted = number_format($order->total, 2, ',', '.');
        $dateFormatted = $order->created_at->format('d/m/Y H:i');

        return <<<MESSAGE
🔔 *NOVO PEDIDO RECEBIDO!*

O cliente *{$order->customer_name}* fez um novo pedido.

📦 Pedido #{$order->id}
💰 Valor: R$ {$totalFormatted}
📅 Data: {$dateFormatted}

Acesse o sistema para ver os detalhes completos.
MESSAGE;
    }

    /**
     * Formata mensagem completa para o cliente com produtos, valor e PIX
     */
    protected function formatCustomerMessage(Order $order): string
    {
        // Formatar itens do pedido
        $items = $order->items->map(function ($item, $index) {
            $product = $item->product;
            $unitInfo = '';
            
            if ($product->unit && $product->unit_value) {
                $unitInfo = " ({$product->unit_value} {$product->unit})";
            }
            
            $priceUnit = number_format($item->price, 2, ',', '.');
            $subtotal = number_format($item->subtotal, 2, ',', '.');
            
            return ($index + 1) . ". *{$product->name}*{$unitInfo}\n   Qtd: {$item->quantity} x R$ {$priceUnit} = R$ {$subtotal}";
        })->implode("\n\n");

        $totalFormatted = number_format($order->total, 2, ',', '.');
        $dateFormatted = $order->created_at->format('d/m/Y H:i');
        
        // Buscar informações de pagamento PIX
        $pixKey = Settings::get('payment_pix_key', '');
        $pixRecipient = Settings::get('payment_recipient_name', '');
        $adminWhatsApp = Settings::get('admin_whatsapp_number', '');
        
        // Formatar número do WhatsApp para exibição
        $adminWhatsAppDisplay = $adminWhatsApp;
        if (!empty($adminWhatsApp) && strlen($adminWhatsApp) >= 11) {
            // Formatar como (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
            if (strlen($adminWhatsApp) == 13) {
                $adminWhatsAppDisplay = '(' . substr($adminWhatsApp, 2, 2) . ') ' . substr($adminWhatsApp, 4, 5) . '-' . substr($adminWhatsApp, 9);
            } elseif (strlen($adminWhatsApp) == 12) {
                $adminWhatsAppDisplay = '(' . substr($adminWhatsApp, 2, 2) . ') ' . substr($adminWhatsApp, 4, 4) . '-' . substr($adminWhatsApp, 8);
            }
        }

        $pixSection = '';
        if (!empty($pixKey)) {
            $pixSection = "\n━━━━━━━━━━━━━━━━━━━━\n";
            $pixSection .= "💳 *PAGAMENTO PIX*\n\n";
            if (!empty($pixRecipient)) {
                $pixSection .= "Favorecido: {$pixRecipient}\n";
            }
            $pixSection .= "Chave PIX: *{$pixKey}*\n";
            $pixSection .= "Valor: *R$ {$totalFormatted}*";
        }

        $comprovanteSection = '';
        if (!empty($adminWhatsApp)) {
            $comprovanteSection = "\n━━━━━━━━━━━━━━━━━━━━\n";
            $comprovanteSection .= "📤 *ENVIE O COMPROVANTE*\n\n";
            $comprovanteSection .= "Após realizar o pagamento, envie o comprovante para:\n";
            $comprovanteSection .= "📱 WhatsApp: {$adminWhatsAppDisplay}";
        }

        return <<<MESSAGE
✅ *Pedido Confirmado!*

Olá, {$order->customer_name}!

Seu pedido #{$order->id} foi recebido com sucesso.

━━━━━━━━━━━━━━━━━━━━
*ITENS DO PEDIDO*

{$items}

━━━━━━━━━━━━━━━━━━━━
💰 *VALOR TOTAL: R$ {$totalFormatted}*
📅 *Data: {$dateFormatted}*{$pixSection}{$comprovanteSection}

Obrigado pela preferência! 🛒
MESSAGE;
    }

    /**
     * Retorna URL do PDF do pedido usando a rota existente
     */
    protected function getOrderPdfUrl(Order $order): string
    {
        // Usa a rota existente de PDF do admin
        return route('admin.orders.pdf', $order);
    }
}


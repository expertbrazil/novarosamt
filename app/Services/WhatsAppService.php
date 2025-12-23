<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected EvolutionApiService $evolutionApi;

    public function __construct(EvolutionApiService $evolutionApi)
    {
        $this->evolutionApi = $evolutionApi;
    }

    /**
     * Retorna instância do EvolutionApiService
     */
    public function getEvolutionApi(): EvolutionApiService
    {
        return $this->evolutionApi;
    }

    public function sendOrder(Order $order): bool
    {
        try {
            $message = $this->formatOrderMessage($order);
            $phoneNumber = $order->customer_phone;

            if (!$phoneNumber) {
                Log::warning('WhatsApp: Número de telefone do cliente não encontrado', ['order_id' => $order->id]);
                return false;
            }

            // Tenta usar Evolution API primeiro se estiver configurada
            if ($this->evolutionApi->isConfigured()) {
                $result = $this->evolutionApi->sendTextMessage($phoneNumber, $message);
                
                if ($result['success']) {
                    $order->update(['whatsapp_sent_at' => now()]);
                    Log::info('Mensagem de pedido enviada via Evolution API', ['order_id' => $order->id]);
                    return true;
                } else {
                    Log::error('Erro ao enviar mensagem via Evolution API', [
                        'order_id' => $order->id,
                        'error' => $result['error'] ?? 'Erro desconhecido'
                    ]);
                    return false;
                }
            }

            // Fallback para sistema antigo (config/services.php)
            $apiUrl = config('services.whatsapp.url');
            $apiToken = config('services.whatsapp.token');
            $defaultPhoneNumber = config('services.whatsapp.phone_number');

            if (!$apiUrl || !$apiToken || !$defaultPhoneNumber) {
                Log::warning('WhatsApp configuration missing - Evolution API e sistema antigo não configurados');
                return false;
            }

            $response = Http::withToken($apiToken)
                ->post($apiUrl, [
                    'phone' => $defaultPhoneNumber,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                $order->update(['whatsapp_sent_at' => now()]);
                return true;
            }

            Log::error('WhatsApp API error', ['response' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function formatOrderMessage(Order $order): string
    {
        // Garantir que os itens estão carregados com produto e categoria
        $order->load('items.product.category');
        
        $items = $order->items->map(function ($item, $index) {
            $product = $item->product;
            $unitInfo = '';
            
            // Formatar informação de unidade se disponível
            if ($product->unit && $product->unit_value) {
                $unitMap = [
                    'kg' => 'kg',
                    'g' => 'g',
                    'l' => 'L',
                    'ml' => 'ml',
                    'cm' => 'cm',
                    'un' => 'un',
                ];
                $unitLabel = $unitMap[strtolower($product->unit)] ?? $product->unit;
                $unitInfo = " ({$product->unit_value} {$unitLabel})";
            } elseif ($product->unit) {
                $unitMap = [
                    'kg' => 'kg',
                    'g' => 'g',
                    'l' => 'L',
                    'ml' => 'ml',
                    'cm' => 'cm',
                    'un' => 'un',
                ];
                $unitLabel = $unitMap[strtolower($product->unit)] ?? $product->unit;
                $unitInfo = " ({$unitLabel})";
            }
            
            $category = $product->category ? " - {$product->category->name}" : '';
            $priceUnit = number_format($item->price, 2, ',', '.');
            $subtotal = number_format($item->subtotal, 2, ',', '.');
            
            return ($index + 1) . ". *{$product->name}*{$category}{$unitInfo}\n   Qtd: {$item->quantity} x R$ {$priceUnit} = R$ {$subtotal}";
        })->implode("\n\n");

        $totalFormatted = number_format($order->total, 2, ',', '.');
        $dateFormatted = $order->created_at->format('d/m/Y H:i');
        $statusLabel = $order->status_label;
        
        // Informações adicionais
        $paymentInfo = '';
        if ($order->payment_method) {
            $paymentMethods = [
                'pix' => 'PIX',
                'credito' => 'Cartão de Crédito',
                'debito' => 'Cartão de Débito',
                'dinheiro' => 'Dinheiro',
                'boleto' => 'Boleto',
                'transferencia' => 'Transferência',
            ];
            $paymentLabel = $paymentMethods[strtolower($order->payment_method)] ?? ucfirst($order->payment_method);
            $paymentInfo = "\n💳 *Forma de Pagamento:* {$paymentLabel}";
        }
        
        $dueDateInfo = '';
        if ($order->due_date) {
            $dueDateInfo = "\n📅 *Data de Vencimento:* {$order->due_date->format('d/m/Y')}";
        }
        
        $observations = '';
        if ($order->observations) {
            $observations = "\n\n📝 *Observações:*\n{$order->observations}";
        }

        return <<<MESSAGE
🛒 *NOVO PEDIDO #{$order->id}*
━━━━━━━━━━━━━━━━━━━━

*INFORMAÇÕES DO CLIENTE*
👤 *Nome:* {$order->customer_name}
📧 *Email:* {$order->customer_email}
📞 *Telefone:* {$order->customer_phone}
🆔 *CPF/CNPJ:* {$order->customer_cpf}
📍 *Endereço:* {$order->customer_address}

━━━━━━━━━━━━━━━━━━━━
*ITENS DO PEDIDO*

{$items}

━━━━━━━━━━━━━━━━━━━━
💰 *TOTAL: R$ {$totalFormatted}*

📊 *Status:* {$statusLabel}{$paymentInfo}{$dueDateInfo}{$observations}

⏰ *Data do Pedido:* {$dateFormatted}
━━━━━━━━━━━━━━━━━━━━
MESSAGE;
    }

    public function sendDirectMessage(string $phoneNumber, string $message): bool
    {
        try {
            // Tenta usar Evolution API primeiro se estiver configurada
            if ($this->evolutionApi->isConfigured()) {
                $result = $this->evolutionApi->sendTextMessage($phoneNumber, $message);
                
                if ($result['success']) {
                    Log::info('Mensagem direta enviada via Evolution API', ['phone' => $phoneNumber]);
                    return true;
                } else {
                    Log::error('Erro ao enviar mensagem direta via Evolution API', [
                        'phone' => $phoneNumber,
                        'error' => $result['error'] ?? 'Erro desconhecido'
                    ]);
                    return false;
                }
            }

            // Fallback para sistema antigo
            $apiUrl = config('services.whatsapp.url');
            $apiToken = config('services.whatsapp.token');

            if (!$apiUrl || !$apiToken) {
                Log::warning('WhatsApp configuration missing - Evolution API e sistema antigo não configurados');
                return false;
            }

            $response = Http::withToken($apiToken)
                ->post($apiUrl, [
                    'phone' => $phoneNumber,
                    'message' => $message,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp direct message error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}


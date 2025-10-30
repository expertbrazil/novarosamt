<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendOrder(Order $order): bool
    {
        try {
            $apiUrl = config('services.whatsapp.url');
            $apiToken = config('services.whatsapp.token');
            $phoneNumber = config('services.whatsapp.phone_number');

            if (!$apiUrl || !$apiToken || !$phoneNumber) {
                Log::warning('WhatsApp configuration missing');
                return false;
            }

            $message = $this->formatOrderMessage($order);

            $response = Http::withToken($apiToken)
                ->post($apiUrl, [
                    'phone' => $phoneNumber,
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
        $items = $order->items->map(function ($item) {
            return "• {$item->product->name} - Qtd: {$item->quantity} - R$ " . number_format($item->subtotal, 2, ',', '.');
        })->implode("\n");

        $totalFormatted = number_format($order->total, 2, ',', '.');
        $dateFormatted = $order->created_at->format('d/m/Y H:i');
        $observations = $order->observations ?? 'Nenhuma observação';

        return <<<MESSAGE
🛒 *NOVO PEDIDO #{$order->id}*

👤 *Cliente:* {$order->customer_name}
📧 *Email:* {$order->customer_email}
📞 *Telefone:* {$order->customer_phone}
🆔 *CPF:* {$order->customer_cpf}
📍 *Endereço:* {$order->customer_address}

📦 *Itens:*
{$items}

💰 *Total: R$ {$totalFormatted}*

📝 *Observações:*
{$observations}

⏰ *Data:* {$dateFormatted}
MESSAGE;
    }

    public function sendDirectMessage(string $phoneNumber, string $message): bool
    {
        try {
            $apiUrl = config('services.whatsapp.url');
            $apiToken = config('services.whatsapp.token');

            if (!$apiUrl || !$apiToken) {
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


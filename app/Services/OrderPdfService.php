<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class OrderPdfService
{
    /**
     * Gera PDF do pedido usando HTML/CSS simples
     * Retorna o caminho do arquivo PDF gerado
     */
    public function generatePdf(Order $order): string
    {
        // Carregar dados do pedido
        $order->load('items.product.category');
        
        // Renderizar HTML do PDF
        $html = View::make('pdf.order', [
            'order' => $order,
            'settings' => \App\Models\Settings::all()->pluck('value', 'key')
        ])->render();
        
        // Usar dompdf se disponível, senão usar alternativa simples
        if (class_exists('\Dompdf\Dompdf')) {
            return $this->generateWithDompdf($html, $order);
        } else {
            // Fallback: salvar HTML e retornar URL
            return $this->generateHtmlFallback($html, $order);
        }
    }
    
    /**
     * Gera PDF usando dompdf
     */
    private function generateWithDompdf(string $html, Order $order): string
    {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'orders/order_' . $order->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($filename, $dompdf->output());
        
        return $filename;
    }
    
    /**
     * Fallback: salva HTML temporário
     */
    private function generateHtmlFallback(string $html, Order $order): string
    {
        $filename = 'orders/order_' . $order->id . '_' . time() . '.html';
        Storage::disk('public')->put($filename, $html);
        
        return $filename;
    }
    
    /**
     * Retorna URL pública do PDF
     */
    public function getPdfUrl(string $filename): string
    {
        return Storage::disk('public')->url($filename);
    }
    
    /**
     * Retorna conteúdo do PDF para download direto
     */
    public function getPdfContent(string $filename): ?string
    {
        if (Storage::disk('public')->exists($filename)) {
            return Storage::disk('public')->get($filename);
        }
        
        return null;
    }
}


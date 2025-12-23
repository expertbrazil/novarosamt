<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public PurchaseOrder $purchaseOrder,
        public string $supplierEmail
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $subject = sprintf('Pedido de Compra #%s - %s', $this->purchaseOrder->id, config('app.name'));

        $email = $this->to($this->supplierEmail)
            ->subject($subject)
            ->view('emails.purchase-orders.supplier', [
                'purchaseOrder' => $this->purchaseOrder,
            ]);

        if ($pdfData = $this->generatePdfAttachment()) {
            $email->attachData(
                $pdfData,
                sprintf('pedido-compra-%s.pdf', str_pad((string) $this->purchaseOrder->id, 4, '0', STR_PAD_LEFT)),
                ['mime' => 'application/pdf']
            );
        }

        return $email;
    }

    /**
     * Tenta gerar o PDF do pedido de compra.
     */
    protected function generatePdfAttachment(): ?string
    {
        try {
            // Como não temos dompdf instalado, vamos usar html2pdf via browser
            // Por enquanto, retornamos null e o PDF será gerado na view
            // Se no futuro instalar dompdf, pode usar o mesmo padrão do OrderInvoiceMail
            return null;
        } catch (\Throwable $exception) {
            Log::warning('Falha ao gerar o PDF do pedido de compra para envio por email.', [
                'purchase_order_id' => $this->purchaseOrder->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return null;
    }
}




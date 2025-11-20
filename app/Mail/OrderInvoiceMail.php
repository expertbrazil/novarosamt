<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $subject = sprintf('Pedido #%s - %s', $this->order->id, config('app.name'));

        $email = $this->subject($subject)
            ->view('emails.orders.invoice', [
                'order' => $this->order,
            ]);

        if ($pdfData = $this->generatePdfAttachment()) {
            $email->attachData(
                $pdfData,
                sprintf('pedido-%s.pdf', str_pad((string) $this->order->id, 4, '0', STR_PAD_LEFT)),
                ['mime' => 'application/pdf']
            );
        }

        return $email;
    }

    /**
     * Tenta gerar o PDF do pedido utilizando o DomPDF caso esteja instalado.
     */
    protected function generatePdfAttachment(): ?string
    {
        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.pdf', [
                    'order' => $this->order,
                ])->output();
            }

            if (app()->bound('dompdf.wrapper')) {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.orders.pdf', ['order' => $this->order]);

                return $pdf->output();
            }
        } catch (\Throwable $exception) {
            Log::warning('Falha ao gerar o PDF do pedido para envio por email.', [
                'order_id' => $this->order->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return null;
    }
}



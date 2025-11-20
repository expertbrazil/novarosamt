<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $order->id }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6; padding: 24px; color: #111827;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="padding: 24px; background: linear-gradient(135deg, #4f46e5, #6366f1); color: #ffffff;">
                <h1 style="margin: 0; font-size: 22px;">Pedido #{{ $order->id }}</h1>
                <p style="margin: 4px 0 0; font-size: 14px; opacity: 0.9;">
                    Enviado em {{ now()->format('d/m/Y H:i') }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="margin: 0 0 16px; font-size: 16px;">Olá {{ $order->customer_name }},</p>
                <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.6;">
                    Segue o resumo do seu pedido. Em anexo você encontra o PDF completo com todos os detalhes.
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 24px;">
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                            <strong>Status:</strong>
                            <div style="font-size: 14px;">{{ $order->status_label }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                            <strong>Total:</strong>
                            <div style="font-size: 18px; color: #4f46e5; font-weight: 600;">
                                R$ {{ number_format($order->total, 2, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                    @if($order->due_date)
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                <strong>Vencimento:</strong>
                                <div>{{ $order->due_date->format('d/m/Y') }}</div>
                            </td>
                        </tr>
                    @endif
                    @if($order->payment_method)
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                <strong>Forma de pagamento:</strong>
                                <div>{{ ucfirst($order->payment_method) }}</div>
                            </td>
                        </tr>
                    @endif
                </table>

                <h2 style="font-size: 16px; margin-bottom: 12px;">Itens do pedido</h2>
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; margin-bottom: 24px;">
                    <thead>
                        <tr>
                            <th align="left" style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 13px; color: #6b7280;">Produto</th>
                            <th align="right" style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 13px; color: #6b7280;">Qtd.</th>
                            <th align="right" style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 13px; color: #6b7280;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
                                    <div style="font-size: 14px; font-weight: 600;">{{ $item->product->name }}</div>
                                    <div style="font-size: 12px; color: #6b7280;">
                                        R$ {{ number_format($item->price, 2, ',', '.') }} un.
                                    </div>
                                </td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px;">
                                    {{ $item->quantity }}
                                </td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; font-weight: 600;">
                                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="font-size: 14px; line-height: 1.6; margin: 0;">
                    Para liberar a entrega, realize o pagamento e envie o comprovante pelo WhatsApp {{ $settings['whatsapp_number'] ?? '' ? '(' . $settings['whatsapp_number'] . ')' : '' }}. Assim conseguimos organizar a logística o quanto antes.
                </p>
            </td>
        </tr>

        @if(!empty($settings['payment_recipient_name']) || !empty($settings['payment_pix_key']))
        <tr>
            <td style="padding: 24px; border-top: 1px solid #e5e7eb;">
                <h2 style="font-size: 16px; margin-bottom: 8px;">Dados para Pagamento</h2>
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse;">
                    @if(!empty($settings['payment_recipient_name']))
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                                <strong>Recebedor:</strong>
                                <div>{{ $settings['payment_recipient_name'] }}</div>
                            </td>
                        </tr>
                    @endif
                    @if(!empty($settings['payment_pix_key']))
                        <tr>
                            <td style="padding: 8px 0;">
                                <strong>Chave PIX:</strong>
                                <div style="font-size: 15px; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ $settings['payment_pix_key'] }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
                <p style="margin: 12px 0 0; font-size: 13px; color: #6b7280; line-height: 1.6;">
                    Após efetuar o pagamento, encaminhe o comprovante pelo WhatsApp para acelerarmos a entrega dos produtos.
                </p>
                @if(!empty($settings['whatsapp_number']))
                    @php
                        $sanitizedWhatsapp = preg_replace('/\D/', '', $settings['whatsapp_number']);
                        $whatsappLink = 'https://wa.me/' . ($sanitizedWhatsapp ?: '');
                    @endphp
                    @if(!empty($sanitizedWhatsapp))
                        <p style="margin: 8px 0 0;">
                            <a href="{{ $whatsappLink }}" style="font-size: 13px; color: #059669; text-decoration: underline;" target="_blank" rel="noopener">
                                Enviar comprovante pelo WhatsApp
                            </a>
                        </p>
                    @endif
                @endif
            </td>
        </tr>
        @endif

        <tr>
            <td style="padding: 24px;">
                <p style="font-size: 14px; line-height: 1.6; margin: 0;">
                    Qualquer dúvida é só responder este e-mail. Obrigado por comprar conosco!
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px; background-color: #f9fafb; text-align: center; font-size: 12px; color: #6b7280;">
                {{ config('app.name') }} • Este e-mail foi enviado automaticamente pelo sistema.
            </td>
        </tr>
    </table>
</body>
</html>



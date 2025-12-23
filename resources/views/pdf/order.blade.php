<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 16px;
            background-color: #f0f0f0;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #333;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEDIDO #{{ $order->id }}</h1>
        <p>Data: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="order-info">
        <div class="section">
            <h2>Dados do Cliente</h2>
            <div class="info-row">
                <span class="info-label">Nome:</span>
                <span class="info-value">{{ $order->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->customer_email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Telefone:</span>
                <span class="info-value">{{ $order->customer_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CPF/CNPJ:</span>
                <span class="info-value">{{ $order->customer_cpf }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Endereço:</span>
                <span class="info-value">{{ $order->customer_address }}</span>
            </div>
        </div>

        <div class="section">
            <h2>Itens do Pedido</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-right">Qtd</th>
                        <th class="text-right">Preço Unit.</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->product->name }}
                            @if($item->product->unit && $item->product->unit_value)
                                ({{ $item->product->unit_value }} {{ $item->product->unit }})
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Informações Adicionais</h2>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">{{ $order->status_label }}</span>
            </div>
            @if($order->payment_method)
            <div class="info-row">
                <span class="info-label">Forma de Pagamento:</span>
                <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
            </div>
            @endif
            @if($order->discount_amount > 0)
            <div class="info-row">
                <span class="info-label">Desconto:</span>
                <span class="info-value">
                    @if($order->discount_type === 'percent')
                        {{ $order->discount_value }}%
                    @else
                        R$ {{ number_format($order->discount_value, 2, ',', '.') }}
                    @endif
                    (R$ {{ number_format($order->discount_amount, 2, ',', '.') }})
                </span>
            </div>
            @endif
            @if($order->observations)
            <div class="info-row">
                <span class="info-label">Observações:</span>
                <span class="info-value">{{ $order->observations }}</span>
            </div>
            @endif
        </div>

        <div class="total">
            <p>TOTAL: R$ {{ number_format($order->total, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ $settings['company_name'] ?? 'Nova Rosa MT' }}</p>
        <p>{{ $settings['company_address'] ?? '' }}</p>
    </div>
</body>
</html>


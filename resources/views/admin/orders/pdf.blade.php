<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $order->id }} - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .company-info {
            flex: 1;
        }
        .order-info {
            text-align: right;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .info-value {
            color: #1f2937;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        thead {
            background-color: #f3f4f6;
        }
        th {
            text-align: left;
            padding: 10px;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        tbody tr:hover {
            background-color: #f9fafb;
        }
        tfoot {
            background-color: #f3f4f6;
        }
        tfoot th {
            text-align: right;
            padding: 15px 10px;
            font-size: 14px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEDIDO #{{ $order->id }}</h1>
        <div class="header-info">
            <div class="company-info">
                @php
                    $logoPath = \App\Models\Settings::get('logo');
                    $companyAddress = \App\Models\Settings::get('company_address');
                @endphp
                @if($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath))
                    <img src="{{ asset('storage/' . $logoPath) }}" 
                         alt="{{ config('app.name') }}" 
                         style="max-height: 60px; max-width: 200px; margin-bottom: 10px;">
                @else
                    <div class="info-value" style="font-weight: bold; font-size: 14px;">{{ config('app.name') }}</div>
                @endif
                @if($companyAddress)
                    <div class="info-value" style="font-size: 10px; color: #6b7280; margin-top: 5px;">
                        {{ $companyAddress }}
                    </div>
                @endif
            </div>
            <div class="order-info">
                <div class="info-item">
                    <div class="info-label">Data do Pedido</div>
                    <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-item" style="margin-top: 10px;">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge" style="background-color: {{ $order->status === 'entregue' ? '#10b981' : ($order->status === 'cancelado' ? '#ef4444' : '#f59e0b') }}; color: white;">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
                @if($order->delivered_at)
                <div class="info-item" style="margin-top: 10px;">
                    <div class="info-label">Data de Entrega</div>
                    <div class="info-value">{{ $order->delivered_at->format('d/m/Y') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informações do Cliente</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nome</div>
                <div class="info-value">{{ $order->customer_name }}</div>
            </div>
            @if($order->customer_email)
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $order->customer_email }}</div>
            </div>
            @endif
            @if($order->customer_phone)
            <div class="info-item">
                <div class="info-label">Telefone</div>
                <div class="info-value">{{ $order->customer_phone }}</div>
            </div>
            @endif
            @if($order->customer_cpf)
            <div class="info-item">
                <div class="info-label">CPF/CNPJ</div>
                <div class="info-value">{{ $order->customer_cpf }}</div>
            </div>
            @endif
            @if($order->customer_address)
            <div class="info-item" style="grid-column: 1 / -1;">
                <div class="info-label">Endereço</div>
                <div class="info-value">{{ $order->customer_address }}</div>
            </div>
            @endif
            @if($order->payment_method)
            <div class="info-item">
                <div class="info-label">Forma de Pagamento</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
            </div>
            @endif
            @if($order->due_date)
            <div class="info-item">
                <div class="info-label">Vencimento</div>
                <div class="info-value">{{ $order->due_date->format('d/m/Y') }}</div>
            </div>
            @endif
        </div>
        @if($order->observations)
        <div class="info-item" style="margin-top: 15px;">
            <div class="info-label">Observações</div>
            <div class="info-value" style="background-color: #f9fafb; padding: 10px; border-radius: 4px; margin-top: 5px;">
                {{ $order->observations }}
            </div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Itens do Pedido</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Foto</th>
                    <th>Produto</th>
                    <th style="text-align: center; width: 80px;">Qtd</th>
                    <th style="text-align: right; width: 100px;">Preço Unit.</th>
                    <th style="text-align: right; width: 120px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="padding: 8px;">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 24px; height: 24px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: bold;">{{ $item->product->name }}</div>
                        @if($item->product->category && $item->product->category->name)
                        <div style="font-size: 10px; color: #6b7280;">{{ $item->product->category->name }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total do Pedido:</th>
                    <th class="total" style="text-align: right;">R$ {{ number_format($order->total, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Este documento foi gerado em {{ now()->format('d/m/Y \à\s H:i') }}</p>
        <p>{{ config('app.name') }} - Sistema de Gestão</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Gerar PDF automaticamente quando a página carregar
        window.onload = function() {
            setTimeout(function() {
                const element = document.body;
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'pedido_{{ $order->id }}_' + new Date().toISOString().split('T')[0] + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                
                html2pdf().set(opt).from(element).save().then(function() {
                    // Fechar a janela após salvar o PDF (opcional)
                    // window.close();
                });
            }, 500);
        };
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido de Compra #{{ $purchaseOrder->id }} - {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.6;
            padding: 24px;
            background-color: #f9fafb;
        }
        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 32px;
            margin-bottom: 32px;
        }
        .header {
            border-bottom: 3px solid #4f46e5;
            margin-bottom: 24px;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 26px;
            color: #111827;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-top: 16px;
        }
        .info-block {
            flex: 1;
        }
        .label {
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .value {
            font-size: 13px;
            color: #111827;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        thead {
            background-color: #eef2ff;
        }
        th, td {
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
        }
        th {
            text-transform: uppercase;
            font-weight: 700;
            color: #4338ca;
            border-bottom: 2px solid #c7d2fe;
        }
        td {
            border-bottom: 1px solid #e5e7eb;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
        }
        .status-rascunho { background-color: #a855f7; }
        .status-enviado { background-color: #10b981; }
        .notes {
            background-color: #f5f3ff;
            padding: 14px;
            border-radius: 8px;
            border: 1px solid #ddd6fe;
            color: #4c1d95;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            margin-top: 32px;
        }
        @media print {
            body { padding: 0; background: #fff; }
            .card { box-shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body>
    <div class="card" id="purchaseOrderExport">
        <div class="header">
            <h1>Pedido de Compra #{{ $purchaseOrder->id }}</h1>
            <div class="header-info">
                <div class="info-block">
                    @php
                        $logo = \App\Models\Settings::get('orders_logo') ?: \App\Models\Settings::get('logo');
                        $companyAddress = \App\Models\Settings::get('company_address');
                    @endphp
                    <div class="label">Empresa</div>
                    @if($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" style="max-height: 60px; margin-bottom: 10px;">
                    @else
                        <div class="value" style="font-weight: 700; font-size: 16px;">{{ config('app.name') }}</div>
                    @endif
                    @if($companyAddress)
                        <div class="value" style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                            {{ $companyAddress }}
                        </div>
                    @endif
                </div>
                <div class="info-block">
                    <div class="label">Status</div>
                    <span class="status-badge status-{{ $purchaseOrder->status }}">
                        {{ $purchaseOrder->status_label }}
                    </span>
                </div>
                <div class="info-block">
                    <div class="label">Criado em</div>
                    <div class="value">{{ optional($purchaseOrder->created_at)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Responsável</div>
                    <div class="value">{{ $purchaseOrder->creator->name ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="label" style="font-size: 12px; color: #4f46e5; margin-bottom: 6px;">Resumo</div>
            <div class="value">
                Este documento lista os produtos que estão abaixo do estoque mínimo e precisam ser comprados.
            </div>
        </div>

        <div class="section">
            <div class="label" style="font-size: 12px; color: #4f46e5; margin-bottom: 6px;">Itens</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Produto</th>
                        <th style="text-align: center;">Qtd.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $index => $item)
                        <tr>
                            <td style="font-weight: 600;">{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $item->product->name }}</div>
                                @if($item->product->category?->name)
                                    <div style="font-size: 11px; color: #6b7280;">{{ $item->product->category->name }}</div>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align: right;">Total de itens</th>
                        <th style="text-align: center;">{{ $purchaseOrder->items->sum('quantity') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($purchaseOrder->notes)
            <div class="section">
                <div class="label" style="font-size: 12px; color: #4f46e5; margin-bottom: 6px;">Observações</div>
                <div class="notes">
                    {{ $purchaseOrder->notes }}
                </div>
            </div>
        @endif

        <div class="footer">
            Documento gerado em {{ now()->format('d/m/Y \\à\\s H:i') }} - {{ config('app.name') }}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        window.onload = function () {
            setTimeout(function () {
                const element = document.getElementById('purchaseOrderExport');
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'pedido_compra_{{ $purchaseOrder->id }}_' + new Date().toISOString().split('T')[0] + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(element).save();
            }, 500);
        };
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Compra #{{ $purchaseOrder->id }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
    <h2>Pedido de Compra #{{ $purchaseOrder->id }}</h2>
    <p>Segue a lista de itens necessários para reposição de estoque.</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Total de itens solicitados:</th>
                <th>{{ $purchaseOrder->items->sum('quantity') }}</th>
            </tr>
        </tfoot>
    </table>

    @if($purchaseOrder->notes)
        <p style="margin-top: 16px;">
            <strong>Observações:</strong> {{ $purchaseOrder->notes }}
        </p>
    @endif
</body>
</html>


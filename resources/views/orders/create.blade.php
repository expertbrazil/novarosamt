@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Fazer Pedido</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('order.store') }}" id="orderForm">
        @csrf

        <!-- Dados do Cliente -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Dados do Cliente</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPF (11 dígitos) *</label>
                    <input type="text" name="customer_cpf" value="{{ old('customer_cpf') }}" 
                           pattern="\d{11}" maxlength="11" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="00000000000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Endereço Completo *</label>
                <textarea name="customer_address" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('customer_address') }}</textarea>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observations" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('observations') }}</textarea>
            </div>
        </div>

        <!-- Produtos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Produtos</h2>
            
            <div id="products-container">
                @foreach($categories as $category)
                    @if($category->products->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ $category->name }}</h3>
                        <div class="space-y-3">
                            @foreach($category->products as $product)
                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">Preço venda: R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }} | Estoque: {{ $product->stock }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="number" 
                                           name="items[{{ $product->id }}][quantity]" 
                                           min="0" 
                                           max="{{ $product->stock }}"
                                           value="0"
                                           data-price="{{ $product->sale_price ?? $product->price }}"
                                           data-product-id="{{ $product->id }}"
                                           class="product-quantity w-20 px-2 py-1 border rounded text-center"
                                           onchange="updateTotal()">
                                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold">Total:</span>
                    <span class="text-2xl font-bold text-blue-600" id="total">R$ 0,00</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('home') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Enviar Pedido
            </button>
        </div>
    </form>
</div>

<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('.product-quantity').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        const price = parseFloat(input.dataset.price) || 0;
        total += quantity * price;
    });
    
    document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    const items = [];
    document.querySelectorAll('.product-quantity').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        if (quantity > 0) {
            items.push({
                product_id: input.dataset.productId,
                quantity: quantity
            });
        }
    });

    if (items.length === 0) {
        e.preventDefault();
        alert('Selecione pelo menos um produto');
        return false;
    }
});
</script>
@endsection


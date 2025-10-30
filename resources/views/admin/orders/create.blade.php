@extends('layouts.admin')

@php
    $title = 'Novo Pedido';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Pedidos
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Novo Pedido</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Novo Pedido
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Crie um novo pedido para um cliente
            </p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.orders.store') }}" id="adminOrderForm" class="space-y-6">
        @csrf
        
        <!-- Customer Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações do Cliente
                </h3>
                
                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Existem {{ $errors->count() }} erro(s) no formulário:
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul role="list" class="list-disc space-y-1 pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <!-- Customer -->
                    <div class="sm:col-span-2">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cliente *
                        </label>
                        <div class="mt-1">
                            <select name="customer_id" 
                                    id="customer_id" 
                                    required 
                                    class="form-input @error('customer_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Selecione um cliente</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (int)($selectedCustomerId ?? old('customer_id')) === $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - CPF: {{ $customer->cpf }}{{ $customer->email ? ' - '.$customer->email : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Data de Vencimento
                        </label>
                        <div class="mt-1">
                            <input type="date" 
                                   name="due_date" 
                                   id="due_date"
                                   value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" 
                                   class="form-input @error('due_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Forma de Pagamento
                        </label>
                        <div class="mt-1">
                            <select name="payment_method" 
                                    id="payment_method"
                                    class="form-input @error('payment_method') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="pix" {{ old('payment_method')=='pix' ? 'selected' : '' }}>PIX</option>
                                <option value="credito" {{ old('payment_method')=='credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="debito" {{ old('payment_method')=='debito' ? 'selected' : '' }}>Cartão de Débito</option>
                                <option value="dinheiro" {{ old('payment_method')=='dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="boleto" {{ old('payment_method')=='boleto' ? 'selected' : '' }}>Boleto</option>
                                <option value="transferencia" {{ old('payment_method')=='transferencia' ? 'selected' : '' }}>Transferência</option>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Itens do Pedido
                    </h3>
                    <button type="button" 
                            id="addItemBtn" 
                            class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Adicionar Item
                    </button>
                </div>
                
                <div id="itemsRepeater" class="space-y-4"></div>

                <template id="itemRowTemplate">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-end">
                            <!-- Product -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Produto *
                                </label>
                                <select class="product-select form-input" required>
                                    <option value="">Selecione um produto...</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->last_purchase_cost ?? ($product->sale_price ?? $product->price) }}" 
                                                        data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} (Estoque: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Preço Unitário
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" 
                                           class="price-display form-input pl-10 bg-gray-100 dark:bg-gray-600" 
                                           readonly 
                                           value="0,00">
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Quantidade
                                </label>
                                <input type="number" 
                                       class="quantity-input form-input text-center" 
                                       min="1" 
                                       value="1">
                            </div>

                            <!-- Subtotal -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Subtotal
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" 
                                           class="subtotal-display form-input pl-10 bg-gray-100 dark:bg-gray-600" 
                                           readonly 
                                           value="0,00">
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="items[IDX][product_id]" class="product-id" value="">
                            <input type="hidden" name="items[IDX][quantity]" class="quantity-hidden" value="1">
                        </div>
                        
                        <!-- Remove Button -->
                        <div class="mt-4 flex justify-end">
                            <button type="button" 
                                    class="remove-item text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remover Item
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Total -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900 dark:text-white">Total do Pedido:</span>
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="total">R$ 0,00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observations -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Observações
                </h3>
                
                <div>
                    <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Observações do Pedido
                    </label>
                    <div class="mt-1">
                        <textarea name="observations" 
                                  id="observations"
                                  rows="4" 
                                  class="form-input @error('observations') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                  placeholder="Adicione observações sobre o pedido, entrega, pagamento, etc...">{{ old('observations') }}</textarea>
                    </div>
                    @error('observations')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.orders.index') }}" 
               class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Criar Pedido
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeater = document.getElementById('itemsRepeater');
    const template = document.getElementById('itemRowTemplate').content;
    const totalEl = document.getElementById('total');

    function formatBRL(value) {
        return 'R$ ' + (Number(value || 0)).toFixed(2).replace('.', ',');
    }

    function parseBRL(value) {
        return parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
    }

    function recalculateTotal() {
        let total = 0;
        repeater.querySelectorAll('.subtotal-display').forEach(subtotalInput => {
            const value = subtotalInput.dataset.value ? Number(subtotalInput.dataset.value) : 0;
            total += value;
        });
        totalEl.textContent = formatBRL(total);
    }

    function bindItemRow(row) {
        const productSelect = row.querySelector('.product-select');
        const priceDisplay = row.querySelector('.price-display');
        const quantityInput = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.subtotal-display');
        const productIdHidden = row.querySelector('.product-id');
        const quantityHidden = row.querySelector('.quantity-hidden');

        function updateRowCalculations() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
            const stock = selectedOption ? Number(selectedOption.dataset.stock || 0) : 0;
            const quantity = Math.min(Number(quantityInput.value || 1), stock || 1);
            
            // Update quantity limits
            quantityInput.max = stock || 1;
            quantityInput.value = quantity;
            
            // Update displays
            priceDisplay.value = price.toFixed(2).replace('.', ',');
            const subtotal = quantity * price;
            subtotalDisplay.value = subtotal.toFixed(2).replace('.', ',');
            subtotalDisplay.dataset.value = subtotal;
            
            // Update hidden fields
            productIdHidden.value = productSelect.value || '';
            quantityHidden.value = quantity;
            
            recalculateTotal();
        }

        productSelect.addEventListener('change', updateRowCalculations);
        quantityInput.addEventListener('input', updateRowCalculations);
        
        row.querySelector('.remove-item').addEventListener('click', function() {
            if (repeater.children.length > 1) {
                row.remove();
                recalculateTotal();
            } else {
                showToast('Deve haver pelo menos um item no pedido', 'warning');
            }
        });

        updateRowCalculations();
    }

    function addItem() {
        const index = repeater.children.length;
        const clone = document.importNode(template, true);
        const row = clone.querySelector('.bg-gray-50');
        
        // Update name attributes
        row.querySelectorAll('input[name], select[name]').forEach(element => {
            if (element.name) {
                element.name = element.name.replace('IDX', index);
            }
        });
        
        repeater.appendChild(clone);
        bindItemRow(repeater.lastElementChild);
    }

    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', addItem);

    // Form validation
    document.getElementById('adminOrderForm').addEventListener('submit', function(e) {
        const rows = repeater.querySelectorAll('.bg-gray-50');
        let hasValidItems = false;
        
        for (const row of rows) {
            const productId = row.querySelector('.product-id').value;
            const quantity = Number(row.querySelector('.quantity-hidden').value || 0);
            
            if (productId && quantity > 0) {
                hasValidItems = true;
                break;
            }
        }
        
        if (!hasValidItems) {
            e.preventDefault();
            showToast('Adicione pelo menos um item válido ao pedido', 'error');
            return false;
        }
    });

    // Initialize with one item
    addItem();
});
</script>
@endsection



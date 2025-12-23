@php
    use App\Models\PurchaseOrder;

    $formId = $formId ?? 'purchaseOrderForm';
    $itemsRepeaterId = $formId . '_itemsRepeater';
    $templateId = $formId . '_itemRowTemplate';
    $addButtonId = $formId . '_addItemBtn';
    $initialItemsPayload = old('items', $initialItems ?? []);
    $statusValue = old('status', $purchaseOrder->status ?? PurchaseOrder::STATUS_DRAFT);
@endphp

<form method="POST" action="{{ $formAction }}" id="{{ $formId }}" class="space-y-6">
    @csrf
    @if(strtoupper($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if($showStatusField ?? false)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <label for="{{ $formId }}_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status do Pedido
                </label>
                <select id="{{ $formId }}_status"
                        name="status"
                        class="form-input max-w-sm">
                    <option value="{{ PurchaseOrder::STATUS_DRAFT }}" {{ $statusValue === PurchaseOrder::STATUS_DRAFT ? 'selected' : '' }}>
                        Rascunho
                    </option>
                    <option value="{{ PurchaseOrder::STATUS_SENT }}" {{ $statusValue === PurchaseOrder::STATUS_SENT ? 'selected' : '' }}>
                        Enviado
                    </option>
                </select>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Itens do Pedido
                </h3>
                <button type="button"
                        id="{{ $addButtonId }}"
                        class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Produto
                </button>
            </div>

            <div id="{{ $itemsRepeaterId }}" class="space-y-4"></div>

            <template id="{{ $templateId }}">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-end">
                        <div class="sm:col-span-7">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Produto *
                            </label>
                            <select class="product-select form-input" required>
                                <option value="">Selecione um produto...</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach($category->products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-stock="{{ $product->stock }}"
                                                    data-min-stock="{{ $product->min_stock }}">
                                                {{ $product->name }} (Estoque: {{ $product->stock }} / Mín: {{ $product->min_stock ?? '—' }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Quantidade
                            </label>
                            <input type="number"
                                   min="1"
                                   class="quantity-input form-input text-center"
                                   value="1">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Estoque Atual
                            </label>
                            <input type="text"
                                   class="stock-display form-input bg-gray-100 dark:bg-gray-600"
                                   value="—"
                                   readonly>
                        </div>
                        <input type="hidden" name="items[IDX][product_id]" class="product-id" value="">
                        <input type="hidden" name="items[IDX][quantity]" class="quantity-hidden" value="1">
                    </div>

                    <div class="mt-4 flex justify-between text-xs text-gray-500 dark:text-gray-300">
                        <span class="min-stock-display">Estoque mínimo: —</span>
                        <button type="button"
                                class="remove-item text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors duration-200">
                            Remover
                        </button>
                    </div>
                </div>
            </template>

            <p class="mt-4 text-sm text-gray-500 dark:text-gray-300">
                Sugestão: acompanhe o estoque mínimo dos produtos para planejar compras antes de ficarem indisponíveis.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <label for="{{ $formId }}_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Observações
            </label>
            <textarea id="{{ $formId }}_notes"
                      name="notes"
                      rows="4"
                      class="form-input"
                      placeholder="Informações adicionais, fornecedor, prazos, etc.">{{ old('notes', $purchaseOrder->notes ?? null) }}</textarea>
        </div>
    </div>

    <div class="flex justify-end flex-wrap gap-3">
        <a href="{{ route('admin.purchase-orders.index') }}"
           class="btn-secondary">
            Cancelar
        </a>
        <button type="submit" class="btn-primary">
            {{ $submitLabel }}
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('{{ $formId }}');
    if (!form) {
        return;
    }

    const repeater = document.getElementById('{{ $itemsRepeaterId }}');
    const template = document.getElementById('{{ $templateId }}').content;
    const initialItems = Object.values(@json($initialItemsPayload));

    function bindRow(row, prefill = null) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const stockDisplay = row.querySelector('.stock-display');
        const minStockDisplay = row.querySelector('.min-stock-display');
        const productIdHidden = row.querySelector('.product-id');
        const quantityHidden = row.querySelector('.quantity-hidden');
        const removeBtn = row.querySelector('.remove-item');

        function syncFields() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stock = selectedOption ? selectedOption.dataset.stock : null;
            const minStock = selectedOption ? selectedOption.dataset.minStock : null;

            stockDisplay.value = typeof stock !== 'undefined' && stock !== null && stock !== '' ? stock : '—';
            minStockDisplay.textContent = 'Estoque mínimo: ' + (minStock && minStock !== '' ? minStock : '—');

            productIdHidden.value = productSelect.value || '';

            let quantity = parseInt(quantityInput.value, 10);
            if (Number.isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }
            quantityInput.value = quantity;
            quantityHidden.value = quantity;
        }

        productSelect.addEventListener('change', syncFields);
        quantityInput.addEventListener('input', syncFields);
        removeBtn.addEventListener('click', () => row.remove());

        if (prefill) {
            productSelect.value = prefill.product_id ?? '';
            quantityInput.value = prefill.quantity ?? 1;
        }

        syncFields();
    }

    function addItem(prefill = null) {
        const index = repeater.children.length;
        const fragment = document.importNode(template, true);
        const row = fragment.querySelector('.bg-gray-50');

        row.querySelectorAll('input[name]').forEach(input => {
            input.name = input.name.replace('IDX', index);
        });

        repeater.appendChild(fragment);
        bindRow(repeater.lastElementChild, prefill);
    }

    const addButton = document.getElementById('{{ $addButtonId }}');
    addButton?.addEventListener('click', () => addItem());

    form.addEventListener('submit', event => {
        const rows = repeater.querySelectorAll('.bg-gray-50');
        let hasValidItems = false;

        rows.forEach(row => {
            const productId = row.querySelector('.product-id')?.value;
            const quantity = Number(row.querySelector('.quantity-hidden')?.value || 0);
            if (productId && quantity > 0) {
                hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            event.preventDefault();
            if (typeof showToast === 'function') {
                showToast('Adicione pelo menos um item ao pedido de compra', 'error');
            } else {
                alert('Adicione pelo menos um item ao pedido de compra');
            }
        }
    });

    if (initialItems.length) {
        initialItems.forEach(item => addItem(item));
    } else {
        addItem();
    }
});
</script>




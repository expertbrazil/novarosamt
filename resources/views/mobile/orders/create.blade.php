@extends('layouts.mobile')

@php
    $title = 'Fazer Pedido';
    $showBack = true;
    $backUrl = route('home');
@endphp

@section('content')
<form method="POST" action="{{ route('order.store') }}" id="orderForm" class="space-y-6">
    @csrf

    <!-- Dados do Cliente -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Dados do Cliente</h2>
        
        <div class="space-y-4">
            <!-- Tipo de Pessoa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Pessoa *</label>
                <select name="customer_person_type" 
                        id="customer_person_type" 
                        required
                        class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="PF" {{ old('customer_person_type', 'PF') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                    <option value="PJ" {{ old('customer_person_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                </select>
                @error('customer_person_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nome -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" id="document_label">Nome Completo *</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('customer_name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- CPF/CNPJ -->
            <div id="cpf_group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CPF *</label>
                <input type="text" id="customer_cpf" name="customer_cpf" value="{{ old('customer_cpf') }}" 
                       maxlength="14"
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="000.000.000-00">
                @error('customer_cpf')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p id="cpf-validation-message" class="mt-1 text-sm hidden"></p>
            </div>

            <div id="cnpj_group" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CNPJ *</label>
                <input type="text" id="customer_cnpj" name="customer_cnpj" value="{{ old('customer_cnpj') }}" 
                       maxlength="18"
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="00.000.000/0000-00">
                @error('customer_cnpj')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p id="cnpj-validation-message" class="mt-1 text-sm hidden"></p>
            </div>

            <!-- Data de Nascimento -->
            <div id="birth_date_group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data de Nascimento</label>
                <input type="date" id="customer_birth_date" name="customer_birth_date" value="{{ old('customer_birth_date') }}"
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('customer_birth_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('customer_email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telefone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefone *</label>
                <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required
                       maxlength="15"
                       class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="(00) 00000-0000">
                @error('customer_phone')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Endereço -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Endereço</h3>
            
            <div class="space-y-4">
                <!-- CEP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CEP *</label>
                    <input type="text" id="customer_cep" name="customer_cep" value="{{ old('customer_cep') }}" 
                           maxlength="9" required
                           class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="00000-000">
                    @error('customer_cep')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rua/Logradouro *</label>
                    <input type="text" id="customer_street" name="customer_street" value="{{ old('customer_street') }}" required
                           class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('customer_street')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número e Complemento -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número *</label>
                        <input type="text" id="customer_number" name="customer_number" value="{{ old('customer_number') }}" required
                               class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('customer_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Complemento</label>
                        <input type="text" id="customer_complement" name="customer_complement" value="{{ old('customer_complement') }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Bairro -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bairro *</label>
                    <input type="text" id="customer_district" name="customer_district" value="{{ old('customer_district') }}" required
                           class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('customer_district')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cidade e Estado -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cidade *</label>
                        <input type="text" id="customer_city" name="customer_city" value="{{ old('customer_city') }}" required
                               class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('customer_city')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">UF *</label>
                        <input type="text" id="customer_state" name="customer_state" value="{{ old('customer_state') }}" required
                               maxlength="2"
                               class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase text-center"
                               placeholder="MT">
                        @error('customer_state')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Itens do Pedido</h2>
            <button type="button" 
                    id="addItemBtn" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium active:scale-95 transition-transform shadow-sm">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar
            </button>
        </div>
        
        <div id="itemsRepeater" class="space-y-4"></div>

        <template id="itemRowTemplate">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                <!-- Produto -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Produto *
                    </label>
                    <select class="product-select w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" required>
                        <option value="">Selecione um produto...</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->sale_price ?? $product->price }}" 
                                            data-stock="{{ $product->stock }}">
                                        {{ $product->name }} (Estoque: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Preço, Quantidade e Subtotal -->
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Preço</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">R$</span>
                            </div>
                            <input type="text" 
                                   class="price-display w-full px-3 py-2 pl-8 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" 
                                   readonly 
                                   value="0,00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Qtd</label>
                        <input type="number" 
                               class="quantity-input w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-center" 
                               min="1" 
                               value="1">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Subtotal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-xs">R$</span>
                            </div>
                            <input type="text" 
                                   class="subtotal-display w-full px-3 py-2 pl-8 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" 
                                   readonly 
                                   value="0,00">
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="items[IDX][product_id]" class="product-id" value="">
                <input type="hidden" name="items[IDX][quantity]" class="quantity-hidden" value="1">
                
                <!-- Remove Button -->
                <button type="button" 
                        class="remove-item w-full py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-medium transition-colors active:scale-95">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remover Item
                </button>
            </div>
        </template>

        <!-- Total -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900 dark:text-white">Total do Pedido:</span>
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="total">R$ 0,00</span>
            </div>
        </div>
    </div>

    <!-- Observações -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Observações</h2>
        <div>
            <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Observações do Pedido
            </label>
            <textarea name="observations" 
                      id="observations"
                      rows="4" 
                      class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Adicione observações sobre o pedido, entrega, pagamento, etc...">{{ old('observations') }}</textarea>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="flex gap-3 pb-4">
        <a href="{{ route('home') }}" class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-center font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 active:scale-95 transition-transform">
            Cancelar
        </a>
        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg active:scale-95 transition-transform">
            Enviar Pedido
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== Tipo de Pessoa ==========
    const personTypeSelect = document.getElementById('customer_person_type');
    const cpfGroup = document.getElementById('cpf_group');
    const cnpjGroup = document.getElementById('cnpj_group');
    const birthDateGroup = document.getElementById('birth_date_group');
    const cpfInput = document.getElementById('customer_cpf');
    const cnpjInput = document.getElementById('customer_cnpj');
    const nameLabel = document.getElementById('document_label');

    function togglePersonType() {
        if (!personTypeSelect) return;
        const personType = personTypeSelect.value;
        
        if (personType === 'PF') {
            if (cpfGroup) cpfGroup.style.display = 'block';
            if (cnpjGroup) cnpjGroup.style.display = 'none';
            if (birthDateGroup) birthDateGroup.style.display = 'block';
            if (cpfInput) cpfInput.required = true;
            if (cnpjInput) cnpjInput.required = false;
            if (cnpjInput) cnpjInput.value = '';
            if (nameLabel) nameLabel.textContent = 'Nome Completo *';
        } else {
            if (cpfGroup) cpfGroup.style.display = 'none';
            if (cnpjGroup) cnpjGroup.style.display = 'block';
            if (birthDateGroup) birthDateGroup.style.display = 'none';
            if (cpfInput) cpfInput.required = false;
            if (cnpjInput) cnpjInput.required = true;
            if (cpfInput) cpfInput.value = '';
            if (nameLabel) nameLabel.textContent = 'Razão Social *';
        }
    }

    if (personTypeSelect) {
        personTypeSelect.addEventListener('change', togglePersonType);
        togglePersonType();
    }

    // ========== Itens do Pedido ==========
    const repeater = document.getElementById('itemsRepeater');
    const template = document.getElementById('itemRowTemplate');
    const totalEl = document.getElementById('total');

    function formatBRL(value) {
        return 'R$ ' + (Number(value || 0)).toFixed(2).replace('.', ',');
    }

    function recalculateTotal() {
        if (!repeater || !totalEl) return;
        let total = 0;
        repeater.querySelectorAll('.subtotal-display').forEach(subtotalInput => {
            const value = subtotalInput.dataset.value ? Number(subtotalInput.dataset.value) : 0;
            total += value;
        });
        totalEl.textContent = formatBRL(total);
    }

    function bindItemRow(row) {
        if (!row) return;
        const productSelect = row.querySelector('.product-select');
        const priceDisplay = row.querySelector('.price-display');
        const quantityInput = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.subtotal-display');
        const productIdHidden = row.querySelector('.product-id');
        const quantityHidden = row.querySelector('.quantity-hidden');

        if (!productSelect || !priceDisplay || !quantityInput || !subtotalDisplay || !productIdHidden || !quantityHidden) return;

        function updateRowCalculations() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
            const stock = selectedOption ? Number(selectedOption.dataset.stock || 0) : 0;
            const quantity = Math.max(1, Number(quantityInput.value || 1));
            
            quantityInput.max = stock || 999999;
            quantityInput.value = quantity;
            
            priceDisplay.value = price.toFixed(2).replace('.', ',');
            const subtotal = quantity * price;
            subtotalDisplay.value = subtotal.toFixed(2).replace('.', ',');
            subtotalDisplay.dataset.value = subtotal;
            
            productIdHidden.value = productSelect.value || '';
            quantityHidden.value = quantity;
            
            recalculateTotal();
        }

        productSelect.addEventListener('change', updateRowCalculations);
        quantityInput.addEventListener('input', updateRowCalculations);
        
        const removeBtn = row.querySelector('.remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (repeater && repeater.children.length > 1) {
                    row.remove();
                    recalculateTotal();
                } else {
                    alert('Deve haver pelo menos um item no pedido');
                }
            });
        }

        updateRowCalculations();
    }

    function addItem() {
        if (!repeater || !template) return;
        const index = repeater.children.length;
        const clone = document.importNode(template.content, true);
        const row = clone.querySelector('.bg-gray-50, .bg-gray-700');
        
        if (!row) return;
        
        row.querySelectorAll('input[name], select[name]').forEach(element => {
            if (element.name) {
                element.name = element.name.replace('IDX', index);
            }
        });
        
        repeater.appendChild(clone);
        bindItemRow(repeater.lastElementChild);
    }

    const addItemBtn = document.getElementById('addItemBtn');
    if (addItemBtn) {
        addItemBtn.addEventListener('click', addItem);
    }

    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            if (!repeater) return;
            const rows = repeater.querySelectorAll('.bg-gray-50, .bg-gray-700');
            let hasValidItems = false;
            
            for (const row of rows) {
                const productId = row.querySelector('.product-id')?.value;
                const quantity = Number(row.querySelector('.quantity-hidden')?.value || 0);
                
                if (productId && quantity > 0) {
                    hasValidItems = true;
                    break;
                }
            }
            
            if (!hasValidItems) {
                e.preventDefault();
                alert('Adicione pelo menos um item válido ao pedido');
                return false;
            }
        });
    }

    if (repeater && template) {
        addItem();
    }

    // ========== Máscaras ==========
    
    // CPF
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})$/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{3})$/, '$1.$2');
            }
            
            e.target.value = value;
            
            const digitsOnly = value.replace(/\D/g, '');
            if (digitsOnly.length === 11) {
                setTimeout(() => {
                    fetchCustomerByCpf(digitsOnly);
                }, 500);
            }
        });
    }

    // CNPJ
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) value = value.slice(0, 14);
            if (value.length > 12) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})$/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})$/, '$1.$2.$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{3})$/, '$1.$2');
            }
            e.target.value = value;
            
            const digitsOnly = value.replace(/\D/g, '');
            if (digitsOnly.length === 14) {
                setTimeout(() => {
                    fetchCustomerByCnpj(digitsOnly);
                }, 500);
            }
        });
    }

    // Telefone
    const phoneInput = document.getElementById('customer_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 10) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });
    }

    // CEP
    const cepInput = document.getElementById('customer_cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            if (value.length > 5) {
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    // Buscar cliente
    let customerSearchTimeout;
    let isAutoFilling = false;
    
    function fetchCustomerByCpf(cpf) {
        if (isAutoFilling) return;
        clearTimeout(customerSearchTimeout);
        customerSearchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('order.find-customer') }}?cpf=${cpf}`);
                const data = await response.json();
                
                if (data.found && data.customer) {
                    isAutoFilling = true;
                    
                    if (data.customer.person_type) {
                        personTypeSelect.value = data.customer.person_type;
                        togglePersonType();
                    }
                    
                    document.getElementById('customer_name').value = data.customer.name || '';
                    document.getElementById('customer_email').value = data.customer.email || '';
                    
                    if (data.customer.birth_date) {
                        document.getElementById('customer_birth_date').value = data.customer.birth_date;
                    }
                    
                    if (data.customer.phone) {
                        const phone = data.customer.phone.replace(/\D/g, '');
                        if (phone.length === 11) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        } else if (phone.length === 10) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        }
                    }
                    
                    document.getElementById('customer_cep').value = data.customer.cep ? data.customer.cep.replace(/(\d{5})(\d{3})/, '$1-$2') : '';
                    document.getElementById('customer_street').value = data.customer.street || '';
                    document.getElementById('customer_number').value = data.customer.number || '';
                    document.getElementById('customer_complement').value = data.customer.complement || '';
                    document.getElementById('customer_district').value = data.customer.district || '';
                    document.getElementById('customer_city').value = data.customer.city || '';
                    document.getElementById('customer_state').value = data.customer.state || '';
                    
                    setTimeout(() => {
                        isAutoFilling = false;
                    }, 1000);
                }
            } catch (error) {
                console.error('Erro ao buscar cliente:', error);
                isAutoFilling = false;
            }
        }, 300);
    }

    function fetchCustomerByCnpj(cnpj) {
        if (isAutoFilling) return;
        clearTimeout(customerSearchTimeout);
        customerSearchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('order.find-customer') }}?cnpj=${cnpj}`);
                const data = await response.json();
                
                if (data.found && data.customer) {
                    isAutoFilling = true;
                    
                    if (data.customer.person_type) {
                        personTypeSelect.value = data.customer.person_type;
                        togglePersonType();
                    }
                    
                    document.getElementById('customer_name').value = data.customer.name || '';
                    document.getElementById('customer_email').value = data.customer.email || '';
                    
                    if (data.customer.phone) {
                        const phone = data.customer.phone.replace(/\D/g, '');
                        if (phone.length === 11) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        } else if (phone.length === 10) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        }
                    }
                    
                    document.getElementById('customer_cep').value = data.customer.cep ? data.customer.cep.replace(/(\d{5})(\d{3})/, '$1-$2') : '';
                    document.getElementById('customer_street').value = data.customer.street || '';
                    document.getElementById('customer_number').value = data.customer.number || '';
                    document.getElementById('customer_complement').value = data.customer.complement || '';
                    document.getElementById('customer_district').value = data.customer.district || '';
                    document.getElementById('customer_city').value = data.customer.city || '';
                    document.getElementById('customer_state').value = data.customer.state || '';
                    
                    setTimeout(() => {
                        isAutoFilling = false;
                    }, 1000);
                }
            } catch (error) {
                console.error('Erro ao buscar cliente:', error);
                isAutoFilling = false;
            }
        }, 300);
    }
});
</script>
@endsection


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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Pessoa *</label>
                    <select name="customer_person_type" 
                            id="customer_person_type" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_person_type') border-red-500 @enderror">
                        <option value="PF" {{ old('customer_person_type', 'PF') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                        <option value="PJ" {{ old('customer_person_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                    </select>
                    @error('customer_person_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" id="document_label">Nome Completo *</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_name') border-red-500 @enderror">
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- CPF (Pessoa Física) -->
                <div id="cpf_group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPF *</label>
                    <input type="text" id="customer_cpf" name="customer_cpf" value="{{ old('customer_cpf') }}" 
                           maxlength="14"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_cpf') border-red-500 @enderror"
                           placeholder="000.000.000-00">
                    @error('customer_cpf')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="cpf-validation-message" class="mt-1 text-sm hidden"></p>
                </div>

                <!-- CNPJ (Pessoa Jurídica) -->
                <div id="cnpj_group" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ *</label>
                    <input type="text" id="customer_cnpj" name="customer_cnpj" value="{{ old('customer_cnpj') }}" 
                           maxlength="18"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_cnpj') border-red-500 @enderror"
                           placeholder="00.000.000/0000-00">
                    @error('customer_cnpj')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="cnpj-validation-message" class="mt-1 text-sm hidden"></p>
                </div>

                <!-- Data de Nascimento (Pessoa Física) -->
                <div id="birth_date_group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                    <input type="date" id="customer_birth_date" name="customer_birth_date" value="{{ old('customer_birth_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_birth_date') border-red-500 @enderror">
                    @error('customer_birth_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_email') border-red-500 @enderror">
                    @error('customer_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
                    <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required
                           maxlength="15"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_phone') border-red-500 @enderror"
                           placeholder="(00) 00000-0000">
                    @error('customer_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Endereço -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Endereço</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">CEP *</label>
                        <input type="text" id="customer_cep" name="customer_cep" value="{{ old('customer_cep') }}" 
                               maxlength="9" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_cep') border-red-500 @enderror"
                               placeholder="00000-000">
                        @error('customer_cep')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rua/Logradouro *</label>
                        <input type="text" id="customer_street" name="customer_street" value="{{ old('customer_street') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_street') border-red-500 @enderror">
                        @error('customer_street')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número *</label>
                        <input type="text" id="customer_number" name="customer_number" value="{{ old('customer_number') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_number') border-red-500 @enderror">
                        @error('customer_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                        <input type="text" id="customer_complement" name="customer_complement" value="{{ old('customer_complement') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bairro *</label>
                        <input type="text" id="customer_district" name="customer_district" value="{{ old('customer_district') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_district') border-red-500 @enderror">
                        @error('customer_district')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cidade *</label>
                        <input type="text" id="customer_city" name="customer_city" value="{{ old('customer_city') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('customer_city') border-red-500 @enderror">
                        @error('customer_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
            </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado (UF) *</label>
                        <input type="text" id="customer_state" name="customer_state" value="{{ old('customer_state') }}" required
                               maxlength="2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 uppercase @error('customer_state') border-red-500 @enderror"
                               placeholder="MT">
                        @error('customer_state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Produtos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Itens do Pedido</h2>
                <button type="button" 
                        id="addItemBtn" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Item
                </button>
            </div>
            
            <div id="itemsRepeater" class="space-y-4"></div>

            <template id="itemRowTemplate">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-end">
                        <!-- Product -->
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Produto *
                            </label>
                            <select class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                <option value="">Selecione um produto...</option>
                @foreach($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                            @foreach($category->products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-price="{{ $product->sale_price ?? $product->price }}" 
                                                    data-stock="{{ $product->stock }}">
                                                {{ $product->name }} 
                                                @if($product->stock > 0)
                                                    (Estoque: {{ $product->stock }})
                                                @else
                                                    (Sem estoque)
                                                @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Preço Unitário
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">R$</span>
                                </div>
                                <input type="text" 
                                       class="price-display w-full px-3 py-2 pl-10 bg-gray-100 border border-gray-300 rounded-lg" 
                                       readonly 
                                       value="0,00">
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Quantidade
                            </label>
                                    <input type="number" 
                                   class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-lg text-center" 
                                   min="1" 
                                   value="1">
                        </div>

                        <!-- Subtotal -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Subtotal
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">R$</span>
                                </div>
                                <input type="text" 
                                       class="subtotal-display w-full px-3 py-2 pl-10 bg-gray-100 border border-gray-300 rounded-lg" 
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
                                class="remove-item text-red-600 hover:text-red-900 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Remover Item
                        </button>
                    </div>
            </div>
            </template>

            <!-- Total -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">Total do Pedido:</span>
                    <span class="text-2xl font-bold text-blue-600" id="total">R$ 0,00</span>
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Observações</h2>
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">
                    Observações do Pedido
                </label>
                <textarea name="observations" 
                          id="observations"
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Adicione observações sobre o pedido, entrega, pagamento, etc...">{{ old('observations') }}</textarea>
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
        togglePersonType(); // Inicializar
    }

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
            
            // Update quantity limits
            quantityInput.max = stock || 999999;
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
        const row = clone.querySelector('.bg-gray-50');
        
        if (!row) return;
        
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
    const addItemBtn = document.getElementById('addItemBtn');
    if (addItemBtn) {
        addItemBtn.addEventListener('click', addItem);
    }

    // Form validation
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            if (!repeater) return;
            const rows = repeater.querySelectorAll('.bg-gray-50');
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

    // Initialize with one item
    if (repeater && template) {
        addItem();
    }

    // ========== Máscaras e Validações ==========
    
    // Máscara de CPF
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            // Aplicar máscara progressivamente
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})$/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{3})$/, '$1.$2');
            }
            
            e.target.value = value;
            
            // Validar CPF quando completo
            const digitsOnly = value.replace(/\D/g, '');
            if (digitsOnly.length === 11) {
                validateCPF(digitsOnly);
                
                // Buscar cliente automaticamente
                setTimeout(() => {
                    fetchCustomerByCpf(digitsOnly);
                }, 500);
            } else {
                const cpfMsg = document.getElementById('cpf-validation-message');
                if (cpfMsg) cpfMsg.classList.add('hidden');
            }
        });
    }

    // Validação de CPF
    function validateCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        
        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
            showCpfValidation(false, 'CPF inválido');
            return false;
        }

        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let digit = 11 - (sum % 11);
        if (digit >= 10) digit = 0;
        if (digit !== parseInt(cpf.charAt(9))) {
            showCpfValidation(false, 'CPF inválido');
            return false;
        }

        sum = 0;
        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
        digit = 11 - (sum % 11);
        if (digit >= 10) digit = 0;
        if (digit !== parseInt(cpf.charAt(10))) {
            showCpfValidation(false, 'CPF inválido');
            return false;
        }

        showCpfValidation(true, 'CPF válido');
        return true;
    }

    function showCpfValidation(valid, message) {
        const msgEl = document.getElementById('cpf-validation-message');
        if (msgEl) {
            msgEl.textContent = message;
            msgEl.classList.remove('hidden', 'text-green-600', 'text-red-600');
            msgEl.classList.add(valid ? 'text-green-600' : 'text-red-600');
        }
    }

    // Máscara de CNPJ
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
            
            // Validar CNPJ quando completo
            const digitsOnly = value.replace(/\D/g, '');
            if (digitsOnly.length === 14) {
                validateCNPJ(digitsOnly);
                
                // Buscar cliente automaticamente
                setTimeout(() => {
                    fetchCustomerByCnpj(digitsOnly);
                }, 500);
            } else {
                const cnpjMsg = document.getElementById('cnpj-validation-message');
                if (cnpjMsg) cnpjMsg.classList.add('hidden');
            }
        });
    }

    // Validação de CNPJ
    function validateCNPJ(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');
        
        if (cnpj.length !== 14 || /^(\d)\1+$/.test(cnpj)) {
            showCnpjValidation(false, 'CNPJ inválido');
            return false;
        }

        let size = cnpj.length - 2;
        let numbers = cnpj.substring(0, size);
        let digits = cnpj.substring(size);
        let sum = 0;
        let pos = size - 7;

        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) pos = 9;
        }

        let result = sum % 11 < 2 ? 0 : 11 - sum % 11;
        if (result != digits.charAt(0)) {
            showCnpjValidation(false, 'CNPJ inválido');
            return false;
        }

        size = size + 1;
        numbers = cnpj.substring(0, size);
        sum = 0;
        pos = size - 7;

        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) pos = 9;
        }

        result = sum % 11 < 2 ? 0 : 11 - sum % 11;
        if (result != digits.charAt(1)) {
            showCnpjValidation(false, 'CNPJ inválido');
            return false;
        }

        showCnpjValidation(true, 'CNPJ válido');
        return true;
    }

    function showCnpjValidation(valid, message) {
        const msgEl = document.getElementById('cnpj-validation-message');
        if (msgEl) {
            msgEl.textContent = message;
            msgEl.classList.remove('hidden', 'text-green-600', 'text-red-600');
            msgEl.classList.add(valid ? 'text-green-600' : 'text-red-600');
        }
    }

    // Buscar cliente por CPF
    let customerSearchTimeout;
    let isAutoFilling = false; // Flag para evitar loops
    function fetchCustomerByCpf(cpf) {
        if (isAutoFilling) return; // Evita loop
        clearTimeout(customerSearchTimeout);
        customerSearchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('order.find-customer') }}?cpf=${cpf}`);
                const data = await response.json();
                
                if (data.found && data.customer) {
                    isAutoFilling = true; // Marca que está preenchendo
                    
                    // Preencher tipo de pessoa se fornecido
                    if (data.customer.person_type) {
                        personTypeSelect.value = data.customer.person_type;
                        togglePersonType();
                    }
                    
                    // Preencher campos automaticamente
                    document.getElementById('customer_name').value = data.customer.name || '';
                    document.getElementById('customer_email').value = data.customer.email || '';
                    
                    // Data de nascimento
                    if (data.customer.birth_date) {
                        document.getElementById('customer_birth_date').value = data.customer.birth_date;
                    }
                    
                    // CPF ou CNPJ
                    if (data.customer.person_type === 'PJ' && data.customer.cnpj) {
                        const cnpj = data.customer.cnpj.replace(/\D/g, '');
                        if (cnpj.length === 14) {
                            document.getElementById('customer_cnpj').value = cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                        }
                    } else if (data.customer.cpf) {
                        const cpfValue = data.customer.cpf.replace(/\D/g, '');
                        if (cpfValue.length === 11) {
                            document.getElementById('customer_cpf').value = cpfValue.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                        }
                    }
                    
                    // Telefone com máscara
                    if (data.customer.phone) {
                        const phone = data.customer.phone.replace(/\D/g, '');
                        if (phone.length === 11) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        } else if (phone.length === 10) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        } else {
                            document.getElementById('customer_phone').value = data.customer.phone;
                        }
                    }
                    
                    // Endereço
                    document.getElementById('customer_cep').value = data.customer.cep ? data.customer.cep.replace(/(\d{5})(\d{3})/, '$1-$2') : '';
                    document.getElementById('customer_street').value = data.customer.street || '';
                    document.getElementById('customer_number').value = data.customer.number || '';
                    document.getElementById('customer_complement').value = data.customer.complement || '';
                    document.getElementById('customer_district').value = data.customer.district || '';
                    document.getElementById('customer_city').value = data.customer.city || '';
                    document.getElementById('customer_state').value = data.customer.state || '';
                    
                    setTimeout(() => {
                        isAutoFilling = false; // Libera após 1 segundo
                    }, 1000);
                }
            } catch (error) {
                console.error('Erro ao buscar cliente:', error);
                isAutoFilling = false;
            }
        }, 300);
    }

    // Buscar cliente por CNPJ
    function fetchCustomerByCnpj(cnpj) {
        if (isAutoFilling) return; // Evita loop
        clearTimeout(customerSearchTimeout);
        customerSearchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('order.find-customer') }}?cnpj=${cnpj}`);
                const data = await response.json();
                
                if (data.found && data.customer) {
                    isAutoFilling = true; // Marca que está preenchendo
                    
                    // Preencher tipo de pessoa se fornecido
                    if (data.customer.person_type) {
                        personTypeSelect.value = data.customer.person_type;
                        togglePersonType();
                    }
                    
                    // Preencher campos automaticamente
                    document.getElementById('customer_name').value = data.customer.name || '';
                    document.getElementById('customer_email').value = data.customer.email || '';
                    
                    // CNPJ
                    if (data.customer.cnpj) {
                        const cnpjValue = data.customer.cnpj.replace(/\D/g, '');
                        if (cnpjValue.length === 14) {
                            document.getElementById('customer_cnpj').value = cnpjValue.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                        }
                    }
                    
                    // Telefone com máscara
                    if (data.customer.phone) {
                        const phone = data.customer.phone.replace(/\D/g, '');
                        if (phone.length === 11) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        } else if (phone.length === 10) {
                            document.getElementById('customer_phone').value = phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        } else {
                            document.getElementById('customer_phone').value = data.customer.phone;
                        }
                    }
                    
                    // Endereço
                    document.getElementById('customer_cep').value = data.customer.cep ? data.customer.cep.replace(/(\d{5})(\d{3})/, '$1-$2') : '';
                    document.getElementById('customer_street').value = data.customer.street || '';
                    document.getElementById('customer_number').value = data.customer.number || '';
                    document.getElementById('customer_complement').value = data.customer.complement || '';
                    document.getElementById('customer_district').value = data.customer.district || '';
                    document.getElementById('customer_city').value = data.customer.city || '';
                    document.getElementById('customer_state').value = data.customer.state || '';
                    
                    setTimeout(() => {
                        isAutoFilling = false; // Libera após 1 segundo
                    }, 1000);
                }
            } catch (error) {
                console.error('Erro ao buscar cliente:', error);
                isAutoFilling = false;
            }
        }, 300);
    }

    // Máscara de telefone
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
            } else if (value.length > 0) {
                value = value.replace(/(\d{0,2})/, '($1');
            }
            e.target.value = value;
        });
    }

    // Máscara de CEP
    const cepInput = document.getElementById('customer_cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            if (value.length > 5) {
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            }
            e.target.value = value;
            
            // Buscar CEP quando completo
            if (value.replace(/\D/g, '').length === 8) {
                fetchCep(value.replace(/\D/g, ''));
            }
        });
        
        // Integração ViaCEP
        let cepSearchTimeout;
        function fetchCep(cep) {
            clearTimeout(cepSearchTimeout);
            cepSearchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    
                    if (!data.erro) {
                        document.getElementById('customer_street').value = data.logradouro || '';
                        document.getElementById('customer_district').value = data.bairro || '';
                        document.getElementById('customer_city').value = data.localidade || '';
                        document.getElementById('customer_state').value = data.uf || '';
                        
                        // Focar no campo número
                        document.getElementById('customer_number').focus();
                    } else {
                        console.log('CEP não encontrado');
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                }
            }, 300);
        }
    }

    // Máscara para estado (UF)
    const stateInput = document.getElementById('customer_state');
    if (stateInput) {
        stateInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
        });
    }
});
</script>
@endsection

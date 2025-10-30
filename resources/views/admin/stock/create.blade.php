@extends('layouts.admin')

@php
    $title = 'Nova Movimentação';
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
                            <a href="{{ route('admin.stock.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Estoque
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Nova Movimentação</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Nova Movimentação de Estoque
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Registre entradas, saídas ou ajustes de estoque
            </p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.stock.store') }}" class="space-y-6">
        @csrf
        
        <!-- Movement Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Detalhes da Movimentação
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

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Product -->
                    <div class="sm:col-span-2">
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Produto *
                        </label>
                        <div class="mt-1">
                            <select name="product_id" 
                                    id="product_id" 
                                    class="form-input @error('product_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    required>
                                <option value="">Selecione um produto</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}
                                            data-stock="{{ $product->stock }}">
                                        {{ $product->name }} (Estoque: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Movement Type -->
                    <div>
                        <label for="direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo de Movimentação *
                        </label>
                        <div class="mt-1">
                            <select name="direction" 
                                    id="direction" 
                                    class="form-input @error('direction') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    required
                                    onchange="toggleCostField()">
                                <option value="in" {{ old('direction', 'in') === 'in' ? 'selected' : '' }}>Entrada</option>
                                <option value="out" {{ old('direction') === 'out' ? 'selected' : '' }}>Saída</option>
                            </select>
                        </div>
                        @error('direction')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Quantidade *
                        </label>
                        <div class="mt-1">
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   step="1" 
                                   min="1" 
                                   value="{{ old('quantity') }}" 
                                   class="form-input @error('quantity') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   required
                                   oninput="calculateTotal()">
                        </div>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Cost (for entries only) -->
                    <div id="unit_cost_group">
                        <label for="unit_cost_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Custo Unitário *
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">R$</span>
                            </div>
                            <input type="text" 
                                   id="unit_cost_display" 
                                   value="{{ old('unit_cost') ? number_format(old('unit_cost'), 2, ',', '.') : '' }}" 
                                   class="form-input pl-10 @error('unit_cost') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="0,00"
                                   oninput="formatCurrency(this); calculateTotal()">
                            <input type="hidden" name="unit_cost" id="unit_cost" value="{{ old('unit_cost') }}">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Obrigatório para entradas de estoque
                        </p>
                        @error('unit_cost')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="moved_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Data e Hora
                        </label>
                        <div class="mt-1">
                            <input type="datetime-local" 
                                   name="moved_at" 
                                   id="moved_at"
                                   value="{{ old('moved_at', now()->format('Y-m-d\\TH:i')) }}" 
                                   class="form-input @error('moved_at') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('moved_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total (calculated field) -->
                    <div id="total_group">
                        <label for="total_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Valor Total
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">R$</span>
                            </div>
                            <input type="text" 
                                   id="total_display" 
                                   class="form-input pl-10 bg-gray-50 dark:bg-gray-700" 
                                   placeholder="0,00" 
                                   readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Calculado automaticamente
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações Adicionais
                </h3>
                
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Motivo/Observação
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="reason" 
                               id="reason"
                               value="{{ old('reason') }}" 
                               class="form-input @error('reason') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               maxlength="255"
                               placeholder="Descreva o motivo da movimentação...">
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Máximo de 255 caracteres
                    </p>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.stock.index') }}" 
               class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>
            <button type="submit" 
                    class="btn-primary"
                    id="submit-btn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span id="submit-text">Registrar Movimentação</span>
            </button>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form
    initializeForm();
    
    // Setup validation
    setupValidation();
});

function initializeForm() {
    // Set initial state based on direction
    toggleCostField();
    
    // Calculate initial total if values exist
    calculateTotal();
}

function toggleCostField() {
    const direction = document.getElementById('direction').value;
    const unitCostGroup = document.getElementById('unit_cost_group');
    const totalGroup = document.getElementById('total_group');
    const unitCostDisplay = document.getElementById('unit_cost_display');
    const unitCostHidden = document.getElementById('unit_cost');
    const totalDisplay = document.getElementById('total_display');
    
    if (direction === 'in') {
        unitCostGroup.classList.remove('hidden');
        totalGroup.classList.remove('hidden');
        unitCostDisplay.required = true;
    } else {
        unitCostGroup.classList.add('hidden');
        totalGroup.classList.add('hidden');
        unitCostDisplay.required = false;
        unitCostDisplay.value = '';
        unitCostHidden.value = '';
        totalDisplay.value = '';
    }
}

function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    value = (parseInt(value || '0') / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    input.value = value;
    
    // Update hidden field with numeric value
    const numericValue = value.replace(/\./g, '').replace(',', '.');
    document.getElementById('unit_cost').value = numericValue;
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitCost = parseFloat(document.getElementById('unit_cost').value) || 0;
    const total = quantity * unitCost;
    
    if (total > 0) {
        document.getElementById('total_display').value = total.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    } else {
        document.getElementById('total_display').value = '';
    }
}

function setupValidation() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    form.addEventListener('submit', function(e) {
        const direction = document.getElementById('direction').value;
        const quantity = document.getElementById('quantity').value;
        const productSelect = document.getElementById('product_id');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        // Validate quantity against stock for exits
        if (direction === 'out' && selectedOption && selectedOption.dataset.stock) {
            const currentStock = parseInt(selectedOption.dataset.stock);
            const requestedQuantity = parseInt(quantity);
            
            if (requestedQuantity > currentStock) {
                e.preventDefault();
                showToast('Quantidade solicitada (' + requestedQuantity + ') é maior que o estoque disponível (' + currentStock + ').', 'error');
                return;
            }
        }
        
        // Validate unit cost for entries
        if (direction === 'in') {
            const unitCost = document.getElementById('unit_cost').value;
            if (!unitCost || parseFloat(unitCost) <= 0) {
                e.preventDefault();
                showToast('Custo unitário é obrigatório para entradas de estoque.', 'error');
                return;
            }
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Registrando...';
        submitBtn.classList.add('opacity-75');
    });
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Hide toast after 4 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 4000);
}
</script>
@endsection



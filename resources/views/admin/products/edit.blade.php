@extends('layouts.admin')

@php
    $title = 'Editar Produto';
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
                            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Produtos
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Editar Produto
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Atualize as informações do produto "{{ $product->name }}"
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                <svg class="w-1.5 h-1.5 mr-1.5 {{ $product->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações Básicas
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
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Categoria *
                        </label>
                        <div class="mt-1">
                            <select name="category_id" 
                                    id="category_id"
                                    required 
                                    class="form-input @error('category_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome do Produto *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   required 
                                   value="{{ old('name', $product->name) }}" 
                                   class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Descrição
                        </label>
                        <div class="mt-1">
                            <textarea name="description" 
                                      id="description"
                                      rows="3" 
                                      class="form-input @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Preços e Margem
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <!-- Cost Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Preço de Custo *
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" 
                                   name="price" 
                                   id="price"
                                   step="0.01" 
                                   min="0" 
                                   required 
                                   value="{{ old('price', $product->price) }}" 
                                   class="form-input pl-10 @error('price') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   oninput="calcSale()">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Profit Margin -->
                    <div>
                        <label for="profit_margin_percent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Margem de Lucro
                        </label>
                        <div class="mt-1 relative">
                            <input type="number" 
                                   name="profit_margin_percent" 
                                   id="profit_margin_percent"
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('profit_margin_percent', $product->profit_margin_percent) }}" 
                                   class="form-input pr-8 @error('profit_margin_percent') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   oninput="calcSale()">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        @error('profit_margin_percent')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Preço de Venda
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" 
                                   name="sale_price" 
                                   id="sale_price"
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('sale_price', $product->sale_price) }}" 
                                   class="form-input pl-10 bg-gray-50 dark:bg-gray-700"
                                   readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Calculado automaticamente
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit & Stock -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Unidade e Estoque
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Unidade de Medida
                        </label>
                        <div class="mt-1">
                            <select name="unit" 
                                    id="unit" 
                                    class="form-input @error('unit') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    onchange="updateUnitPlaceholder()">
                                <option value="">Selecione</option>
                                <option value="kg" {{ old('unit', $product->unit)=='kg' ? 'selected' : '' }}>Quilogramas (kg)</option>
                                <option value="l" {{ old('unit', $product->unit)=='l' ? 'selected' : '' }}>Litros (l)</option>
                                <option value="g" {{ old('unit', $product->unit)=='g' ? 'selected' : '' }}>Gramas (g)</option>
                                <option value="ml" {{ old('unit', $product->unit)=='ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                                <option value="cm" {{ old('unit', $product->unit)=='cm' ? 'selected' : '' }}>Centímetros (cm)</option>
                                <option value="un" {{ old('unit', $product->unit)=='un' ? 'selected' : '' }}>Unidade (un)</option>
                            </select>
                        </div>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Value -->
                    <div>
                        <label for="unit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Quantidade por Unidade
                        </label>
                        <div class="mt-1">
                            <input type="number" 
                                   name="unit_value" 
                                   id="unit_value"
                                   step="0.001" 
                                   min="0.001" 
                                   value="{{ old('unit_value', $product->unit_value) }}" 
                                   class="form-input @error('unit_value') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="unit_hint">
                            Selecione uma unidade primeiro
                        </p>
                        @error('unit_value')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Estoque Atual
                        </label>
                        <div class="mt-1">
                            <input type="number" 
                                   name="stock" 
                                   id="stock"
                                   min="0" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   class="form-input bg-gray-50 dark:bg-gray-700"
                                   readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Use o módulo de Estoque para gerenciar
                        </p>
                    </div>

                    <!-- Min Stock -->
                    <div>
                        <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Estoque Mínimo
                        </label>
                        <div class="mt-1">
                            <input type="number" 
                                   name="min_stock" 
                                   id="min_stock"
                                   min="0" 
                                   value="{{ old('min_stock', $product->min_stock) }}" 
                                   class="form-input @error('min_stock') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="0">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Quantidade mínima recomendada
                        </p>
                        @error('min_stock')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Image & Status -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Imagem e Status
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Imagem do Produto
                        </label>
                        @if($product->image)
                            <div class="mt-2 mb-4">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="h-32 w-32 rounded-lg object-cover">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Imagem atual</p>
                            </div>
                        @endif
                        <div class="mt-1">
                            <input type="file" 
                                   name="image" 
                                   id="image"
                                   accept="image/*" 
                                   class="form-input @error('image') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            PNG, JPG ou JPEG até 2MB. Deixe vazio para manter a atual.
                        </p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-start pt-6">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active"
                                   value="1" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">
                                Produto ativo
                            </label>
                            <p class="text-gray-500 dark:text-gray-400">
                                Produtos ativos ficam visíveis no sistema
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" 
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
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function calcSale() {
        const price = parseFloat(document.getElementById('price').value || 0);
        const margin = parseFloat(document.getElementById('profit_margin_percent').value || 0);
        const sale = (price * (1 + (margin/100))) || 0;
        document.getElementById('sale_price').value = sale.toFixed(2);
    }
    
    function updateUnitPlaceholder() {
        const unit = document.getElementById('unit').value;
        const hint = document.getElementById('unit_hint');
        
        switch(unit) {
            case 'kg':
                hint.textContent = 'Informe em kg (ex.: 1, 2, 0.5)';
                break;
            case 'l':
                hint.textContent = 'Informe em litros (ex.: 1, 2, 0.75)';
                break;
            case 'g':
                hint.textContent = 'Informe em gramas (ex.: 500, 250)';
                break;
            case 'ml':
                hint.textContent = 'Informe em ml (ex.: 500, 250)';
                break;
            case 'cm':
                hint.textContent = 'Informe em centímetros (ex.: 10, 25, 50)';
                break;
            case 'un':
                hint.textContent = 'Informe a quantidade de unidades (ex.: 1, 2, 10)';
                break;
            default:
                hint.textContent = 'Selecione uma unidade primeiro';
        }
    }
    
    // Make functions global
    window.calcSale = calcSale;
    window.updateUnitPlaceholder = updateUnitPlaceholder;
    
    // Initialize
    calcSale();
    updateUnitPlaceholder();
});
</script>
@endsection


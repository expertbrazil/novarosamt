@extends('layouts.admin')

@php
    $title = 'Produtos';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Produtos</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Gerencie o catálogo de produtos do sistema
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex space-x-3">
            <a href="{{ route('admin.products.index', ['stock_max' => 0]) }}" 
               class="btn-secondary relative">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Estoque Zerado
                @if($zeroStockCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                        {{ $zeroStockCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.products.index', ['low_stock' => 1]) }}" 
               class="btn-secondary relative">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Estoque Mínimo
                @if($lowStockCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-yellow-600 rounded-full">
                        {{ $lowStockCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.products.create') }}" 
               class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Produto
            </a>
        </div>
    </div>

    @if($zeroStockCount > 0 && !request()->has('stock_max'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Atenção: {{ $zeroStockCount }} produto(s) com estoque zerado!
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Clique em "Estoque Zerado" para visualizar a lista completa.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($lowStockCount > 0 && !request()->has('low_stock') && !request()->has('stock_max'))
        <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        Atenção: {{ $lowStockCount }} produto(s) com estoque no mínimo!
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p>Clique em "Estoque Mínimo" para visualizar a lista completa e enviar ao fornecedor.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Search -->
                    <div class="sm:col-span-2">
                        <label for="search" class="sr-only">Buscar produtos</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}" 
                                   class="form-input pl-10" 
                                   placeholder="Buscar por nome ou descrição...">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="sr-only">Filtrar por categoria</label>
                        <select name="category_id" id="category_id" class="form-input">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="sr-only">Filtrar por status</label>
                        <select name="status" id="status" class="form-input">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <!-- Stock Range -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Faixa de Estoque
                        </label>
                        <div class="flex space-x-2">
                            <input type="number" 
                                   name="stock_min" 
                                   value="{{ request('stock_min') }}" 
                                   placeholder="Mín." 
                                   class="form-input">
                            <input type="number" 
                                   name="stock_max" 
                                   value="{{ request('stock_max') }}" 
                                   placeholder="Máx." 
                                   class="form-input">
                        </div>
                    </div>

                    <!-- With Orders -->
                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="with_orders" 
                                   value="1" 
                                   {{ request('with_orders') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Com pedidos</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="sm:col-span-2 flex space-x-2">
                        <button type="submit" class="btn-primary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                            </svg>
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'category_id', 'status', 'stock_min', 'stock_max', 'with_orders']))
                            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request()->has('stock_max') && request('stock_max') == 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Produtos com estoque zerado
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-300">
                            Exporte esta lista para enviar ao fornecedor
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.products.export.zero-stock') }}" 
                   class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Exportar para CSV
                </a>
            </div>
        </div>
    @endif

    @if(request()->has('low_stock') && request('low_stock') == 1)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Produtos com estoque abaixo do mínimo
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            Exporte esta lista para enviar ao fornecedor
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.products.export.low-stock') }}" 
                   class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Exportar para CSV
                </a>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produto
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Preço Compra
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Preço Venda
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estoque
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estoque Mínimo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pedidos
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Ações</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            @if($product->image)
                                                <img src="{{ $product->image_url }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="h-16 w-16 rounded-lg object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1">
                                                {{ $product->name }}
                                            </div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                                    {{ $product->description }}
                                                </div>
                                            @endif
                                            @if($product->unit)
                                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $product->unit_value }} {{ $product->unit }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($product->last_purchase_cost !== null)
                                            <span class="font-medium">R$ {{ number_format($product->last_purchase_cost, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-medium">R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium {{ $product->stock <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm {{ $product->stock <= $product->min_stock ? 'font-bold text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $product->min_stock }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $product->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                                                title="Clique para {{ $product->is_active ? 'inativar' : 'ativar' }}">
                                            <svg class="w-1.5 h-1.5 mr-1.5 {{ $product->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->order_items_count }} pedidos
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200"
                                           title="Ver detalhes">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.products.duplicate', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                           title="Duplicar produto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                           title="Editar produto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($product->order_items_count == 0)
                                            <form method="POST" 
                                                  action="{{ route('admin.products.destroy', $product) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                                        title="Excluir produto">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600" title="Não é possível excluir: produto possui pedidos">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum produto encontrado</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(request()->hasAny(['search', 'category_id', 'status', 'stock_min', 'stock_max', 'with_orders']))
                        Tente ajustar os filtros ou criar um novo produto.
                    @else
                        Comece criando seu primeiro produto.
                    @endif
                </p>
                <div class="mt-6">
                    @if(request()->hasAny(['search', 'category_id', 'status', 'stock_min', 'stock_max', 'with_orders']))
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary mr-3">
                            Limpar Filtros
                        </a>
                    @endif
                    <a href="{{ route('admin.products.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Novo Produto
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 rounded-lg shadow">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection


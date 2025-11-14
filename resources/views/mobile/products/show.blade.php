@extends('layouts.mobile')

@php
    $title = $product->name;
    $showBack = true;
    $backUrl = route('home');
@endphp

@section('content')
<!-- Product Image -->
@if($product->image)
<div class="mb-4">
    <img src="{{ $product->image_url }}" 
         alt="{{ $product->name }}" 
         class="w-full h-64 object-cover rounded-xl shadow-lg">
</div>
@else
<div class="mb-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl h-64 flex items-center justify-center">
    <svg class="w-24 h-24 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
</div>
@endif

<!-- Product Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
    <div class="mb-4">
        @if($product->category)
        <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 text-xs font-medium rounded-full mb-2">
            {{ $product->category->name }}
        </span>
        @endif
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $product->name }}</h1>
    </div>

    @if($product->description)
    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $product->description }}</p>
    @endif

    <!-- Unit Info -->
    @if($product->unit || $product->unit_value)
    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidade</p>
        <p class="text-gray-900 dark:text-white">
            @php
                $unitMap = [
                    'l' => 'Litros',
                    'ml' => 'Mililitros',
                    'kg' => 'Quilogramas',
                    'g' => 'Gramas',
                    'un' => 'Unidades',
                    'cm' => 'Centímetros',
                    'm' => 'Metros'
                ];
                $fullUnit = $unitMap[strtolower($product->unit ?? '')] ?? ($product->unit ?? '');
                $displayValue = $product->unit_value ? rtrim(rtrim(number_format((float)$product->unit_value, 3, ',', '.'), '0'), ',') : '';
            @endphp
            @if($product->unit_value && $product->unit)
                {{ $displayValue }} {{ $fullUnit }}
            @elseif($product->unit)
                {{ $fullUnit }}
            @endif
        </p>
    </div>
    @endif

    <!-- Pricing -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Preço de Custo</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                R$ {{ number_format($product->price, 2, ',', '.') }}
            </p>
        </div>
        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400 mb-1">Preço de Venda</p>
            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
            </p>
        </div>
    </div>

    @if($product->profit_margin_percent)
    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Margem de Lucro</p>
        <p class="text-gray-900 dark:text-white">{{ number_format($product->profit_margin_percent, 1, ',', '.') }}%</p>
    </div>
    @endif

    <!-- Stock Info -->
    <div class="p-3 rounded-lg {{ $product->stock > 0 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} mb-1">
                    Estoque
                </p>
                <p class="text-xl font-bold {{ $product->stock > 0 ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">
                    {{ $product->stock }} {{ $product->unit ?? 'unidades' }}
                </p>
            </div>
            @if($product->min_stock)
            <div class="text-right">
                <p class="text-xs text-gray-600 dark:text-gray-400">Estoque Mínimo</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->min_stock }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Actions -->
<div class="space-y-3">
    @auth
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('admin.products.edit', $product) }}" 
           class="flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium active:scale-95 transition-transform shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </a>
        <a href="{{ route('admin.products.show', $product) }}" 
           class="flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg font-medium active:scale-95 transition-transform shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Detalhes
        </a>
    </div>
    @else
    <a href="{{ route('order.create') }}" 
       class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold active:scale-95 transition-transform shadow-lg">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        Adicionar ao Pedido
    </a>
    @endauth
</div>

<!-- Additional Info -->
@if($product->last_purchase_cost || $product->last_purchase_at)
<div class="mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Última Compra</h3>
    <div class="space-y-2">
        @if($product->last_purchase_cost)
        <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Custo:</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                R$ {{ number_format($product->last_purchase_cost, 2, ',', '.') }}
            </span>
        </div>
        @endif
        @if($product->last_purchase_at)
        <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Data:</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $product->last_purchase_at->format('d/m/Y') }}
            </span>
        </div>
        @endif
    </div>
</div>
@endif
@endsection


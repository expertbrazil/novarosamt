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
        <span class="inline-block px-3 py-1 bg-indigo-600 dark:bg-indigo-600 text-white dark:text-white text-xs font-medium rounded-full mb-2">
            {{ $product->category->name }}
        </span>
        @endif
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $product->name }}</h1>
    </div>

    @if($product->description)
    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</p>
        <p class="text-gray-900 dark:text-white">{{ $product->description }}</p>
    </div>
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
    <div class="mb-4">
        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
            <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400 mb-1">Preço de Venda</p>
            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
            </p>
        </div>
    </div>

    @if($product->description)
    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</p>
        <p class="text-gray-900 dark:text-white">{{ $product->description }}</p>
    </div>
    @endif
</div>

<!-- Actions -->
<div class="space-y-3">
    @php
        $cart = session('cart', []);
        $inCart = false;
        $cartQuantity = 0;
        foreach ($cart as $item) {
            if ($item['product_id'] == $product->id) {
                $inCart = true;
                $cartQuantity = $item['quantity'];
                break;
            }
        }
    @endphp
    
    @if($inCart)
        <a href="{{ route('cart.index') }}" 
           class="flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white rounded-lg font-semibold active:scale-95 transition-transform shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Adicionado ao Carrinho ({{ $cartQuantity }})
        </a>
    @else
        <form method="POST" action="{{ route('cart.add') }}" class="w-full">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold active:scale-95 transition-transform shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Adicionar ao Carrinho
            </button>
        </form>
    @endif
</div>

<!-- Additional Info -->
@if($product->last_purchase_at)
<div class="mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Última Compra</h3>
    <div class="space-y-2">
        <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Data:</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $product->last_purchase_at->format('d/m/Y') }}
            </span>
        </div>
    </div>
</div>
@endif
@endsection


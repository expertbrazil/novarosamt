@extends('layouts.mobile')

@php
    $title = 'Início';
@endphp

@section('content')
<!-- Hero Section -->
<div class="mb-6">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Nova Rosa MT</h1>
        <p class="text-indigo-100 text-sm mb-4">Produtos de limpeza profissionais e domésticos</p>
        <a href="{{ route('order.create') }}" class="inline-flex items-center justify-center w-full bg-white text-indigo-600 font-semibold py-3 px-4 rounded-lg shadow-lg active:scale-95 transition-transform">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Fazer Pedido
        </a>
    </div>
</div>

<!-- Categories & Products -->
@if($categories->count() > 0)
    @foreach($categories as $category)
        @if($category->products->count() > 0)
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h2>
                <a href="{{ route('category.show', $category->slug) }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    Ver todos
                </a>
            </div>
            
            <div class="space-y-3">
                @foreach($category->products->take(5) as $product)
                <a href="{{ route('product.show', $product->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden active:scale-[0.98] transition-transform">
                    <div class="flex">
                        @if($product->image)
                        <div class="w-24 h-24 flex-shrink-0">
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-24 h-24 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1 p-4 flex flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1 line-clamp-1">{{ $product->name }}</h3>
                                @if($product->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1 mb-2">{{ $product->description }}</p>
                                @endif
                                @if($product->unit || $product->unit_value)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @php
                                        $unitMap = [
                                            'l' => 'L', 'ml' => 'mL', 'kg' => 'kg', 'g' => 'g',
                                            'un' => 'un', 'cm' => 'cm', 'm' => 'm'
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
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
                                </span>
                                @if($product->stock > 0)
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">
                                    Estoque: {{ $product->stock }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    @endforeach
@else
<div class="text-center py-12">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum produto disponível</h3>
    <p class="text-gray-600 dark:text-gray-300 text-sm">Em breve teremos produtos disponíveis.</p>
</div>
@endif

<!-- Delivery Info -->
@if($companyAddress || count($deliveryCities) > 0)
<div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Entrega Rápida</h3>
    
    @if($companyAddress)
    <div class="flex items-start mb-3">
        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $companyAddress }}</p>
    </div>
    @endif
    
    @if(count($deliveryCities) > 0)
    <div>
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cidades Atendidas:</p>
        <div class="flex flex-wrap gap-2">
            @foreach($deliveryCities->take(6) as $city)
            <span class="inline-block bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-lg text-xs font-medium">
                {{ $city->municipio }}/{{ $city->estado }}
            </span>
            @endforeach
            @if(count($deliveryCities) > 6)
            <span class="inline-block text-gray-500 dark:text-gray-400 text-xs px-3 py-1">
                +{{ count($deliveryCities) - 6 }} mais
            </span>
            @endif
        </div>
    </div>
    @endif
</div>
@endif
@endsection


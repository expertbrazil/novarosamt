@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Produtos de Limpeza
                <span class="block text-indigo-200">Profissionais</span>
            </h1>
            <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                Encontre os melhores produtos de limpeza para sua casa ou empresa. 
                Qualidade garantida e entrega rápida{{ $companyAddress ? ' em ' . $companyAddress : '' }}.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('order.create') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Fazer Pedido Agora
                </a>
                <a href="#produtos" 
                   class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-white hover:text-indigo-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                    Ver Produtos
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Por que escolher nossos produtos?</h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">Qualidade, eficiência e confiança em cada produto</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Qualidade Garantida</h3>
                <p class="text-gray-600 dark:text-gray-300">Produtos testados e aprovados para máxima eficiência na limpeza</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Entrega Rápida</h3>
                <p class="text-gray-600 dark:text-gray-300">Receba seus produtos rapidamente{{ $companyAddress ? ' em ' . $companyAddress : ' em nossa região' }}</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Melhor Preço</h3>
                <p class="text-gray-600 dark:text-gray-300">Preços competitivos e condições especiais para grandes volumes</p>
            </div>
        </div>
    </div>
</div>

<!-- Products Section -->
<div id="produtos" class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($categories->count() > 0)
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                <section class="mb-16 last:mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $category->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-300">{{ $category->description ?? 'Produtos de alta qualidade para suas necessidades' }}</p>
                        </div>
                        <a href="{{ route('category.show', $category->slug) }}" 
                           class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors duration-200 mt-4 sm:mt-0">
                            Ver todos
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($category->products->take(4) as $product)
                        <a href="{{ route('product.show', $product->id) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group block focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <div class="relative">
                                @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                            R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
                                        </span>
                                        @if($product->unit || $product->unit_value)
                                        <div class="flex items-center mt-1">
                                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                @php
                                                    $unitMap = [
                                                        'l' => 'Litros',
                                                        'ml' => 'Mililitros',
                                                        'kg' => 'Quilogramas',
                                                        'g' => 'Gramas',
                                                        'un' => 'Unidades',
                                                        'pc' => 'Peças',
                                                        'm' => 'Metros',
                                                        'cm' => 'Centímetros',
                                                        'm²' => 'Metros Quadrados',
                                                        'm³' => 'Metros Cúbicos',
                                                    ];
                                                    $fullUnit = $unitMap[strtolower($product->unit ?? '')] ?? ($product->unit ?? '');
                                                    $displayValue = $product->unit_value ? rtrim(rtrim(number_format((float)$product->unit_value, 3, ',', '.'), '0'), ',') : '';
                                                @endphp
                                                @if($product->unit_value && $product->unit)
                                                    {{ $displayValue }} {{ $fullUnit }}
                                                @elseif($product->unit)
                                                    {{ $fullUnit }}
                                                @endif
                                            </span>
                                        </div>
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
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum produto disponível</h3>
                <p class="text-gray-600 dark:text-gray-300">Em breve teremos produtos disponíveis para você.</p>
            </div>
        @endif
    </div>
</div>

<!-- Entrega Section -->
@if($companyAddress || count($deliveryCities) > 0)
<div class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Entrega Rápida</h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Receba seus produtos rapidamente{{ $companyAddress ? ' em ' . $companyAddress : '' }}
            </p>
        </div>

        @if($companyAddress)
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">{{ $companyAddress }}</span>
            </div>
        </div>
        @endif

        @if(count($deliveryCities) > 0)
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 text-center">
                Cidades Atendidas
            </h3>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($deliveryCities as $city)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-center min-w-[150px]">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $city->municipio }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $city->estado }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($deliveryInfo)
        <div class="mt-8 p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $deliveryInfo }}</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- CTA Section -->
<div class="bg-indigo-600 dark:bg-indigo-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Pronto para fazer seu pedido?</h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Entre em contato conosco e receba os melhores produtos de limpeza com entrega rápida e segura.
            </p>
            <a href="{{ route('order.create') }}" 
               class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Fazer Pedido Agora
            </a>
        </div>
    </div>
</div>
@endsection


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
                Qualidade garantida e entrega rápida diretamente na sua casa.
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
                <p class="text-gray-600 dark:text-gray-300">Receba seus produtos rapidamente onde estiver.</p>
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

<!-- Search + Quick Categories (below features) -->
<div class="py-10 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search -->
        <form action="{{ route('home') }}" method="GET" class="mb-6">
            <div class="relative max-w-2xl mx-auto">
                <div class="absolute inset-y-0 left-0 w-14 flex items-center justify-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Busque por nome ou descrição de produto..."
                       class="w-full pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       style="padding-left: 3.5rem;">
            </div>
        </form>

        <!-- Quick Category Cards (only with active products) -->
        @php
            $categoriesWithProducts = $categories->filter(fn($c) => $c->products->count() > 0);
        @endphp
        @if($categoriesWithProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
            @foreach($categoriesWithProducts as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="group block rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1 truncate" title="{{ $cat->name ?? 'Categoria' }}">{{ $cat->name ?? 'Categoria' }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $cat->products->count() }} {{ $cat->products->count() == 1 ? 'item' : 'itens' }}</div>
            </a>
            @endforeach
        </div>
        @endif
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

                    @php $prodCount = $category->products->count(); @endphp
                    @if($prodCount <= 1)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($category->products as $product)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                                    <a href="{{ route('product.show', $product->id) }}" class="block focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <div class="relative">
                                            @if($product->image)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="p-6">
                                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $product->name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="px-6 pb-6">
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
                                            <div class="flex gap-2 items-center">
                                                <a href="{{ route('cart.index') }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-center">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Adicionado ({{ $cartQuantity }})
                                                </a>
                                                <a href="{{ route('cart.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Ver carrinho">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('cart.add') }}" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Adicionar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <style>
                            .category-slider{overflow:hidden}
                            .category-slider .slider-track{display:flex;gap:1.5rem;will-change:transform}
                            .category-slider:hover .slider-track{animation-play-state: paused}
                            .category-slider .slide{flex:0 0 calc((100% - 3*1.5rem)/4)} /* 4 visible (3 gaps) */
                            @media (max-width: 1024px){ /* md/lg boundary */
                                .category-slider .slide{flex:0 0 calc((100% - 1*1.5rem)/2)} /* 2 visible */
                            }
                            @media (max-width: 640px){ /* sm */
                                .category-slider .slide{flex:0 0 100%} /* 1 visible */
                            }
                            @keyframes slider-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
                        </style>
                        <div class="category-slider">
                            <div class="slider-track" data-speed="70">
                                @foreach($category->products as $product)
                                <div class="slide bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                                    <a href="{{ route('product.show', $product->id) }}" class="block focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <div class="relative">
                                            @if($product->image)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="p-6">
                                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $product->name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="px-6 pb-6">
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
                                            <div class="flex gap-2 items-center">
                                                <a href="{{ route('cart.index') }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-center">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Adicionado ({{ $cartQuantity }})
                                                </a>
                                                <a href="{{ route('cart.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Ver carrinho">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('cart.add') }}" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Adicionar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <script>
                            (function(){
                                const container = document.currentScript.previousElementSibling;
                                const track = container && container.querySelector('.slider-track');
                                if(!track) return;
                                // Duplicate children to create seamless loop
                                track.innerHTML = track.innerHTML + track.innerHTML;
                                // After render, compute total width and set animation duration
                                requestAnimationFrame(() => {
                                    const totalWidth = Array.from(track.children).reduce((w, el)=> w + el.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || 24), 0);
                                    const speed = Number(track.dataset.speed || 70); // px per second
                                    const duration = Math.max(10, Math.round(totalWidth / speed));
                                    track.style.animation = `slider-scroll ${duration}s linear infinite`;
                                });
                            })();
                        </script>
                    @endif
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
@if(count($deliveryCities) > 0 || $deliveryInfo)
<div class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Entrega Rápida</h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Receba seus produtos diretamente na sua casa com toda a comodidade que você merece.
            </p>
        </div>

        @if(count($deliveryCities) > 0)
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 text-center">
                Cidades Atendidas
            </h3>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($deliveryCities as $city)
                <div class="bg-indigo-600 dark:bg-indigo-600 rounded-lg p-3 text-center min-w-[150px]">
                    <p class="text-sm font-medium text-white dark:text-white">{{ $city->municipio }}</p>
                    <p class="text-xs text-indigo-100 dark:text-indigo-100">{{ $city->estado }}</p>
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

@php
    $hasWhatsApp = !empty($settings['whatsapp_number']);
    $rawWhatsApp = $hasWhatsApp ? preg_replace('/\D/', '', $settings['whatsapp_number']) : null;
@endphp

<div class="bg-gray-50 dark:bg-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 {{ $hasWhatsApp ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }}">
            @if($hasWhatsApp)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide mb-2">Contato</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Fale com a gente pelo WhatsApp</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Estamos prontos para tirar dúvidas, enviar orçamentos e receber seus pedidos.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="https://wa.me/{{ $rawWhatsApp }}" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center w-full px-6 py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold shadow-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 12.487l-.028-.018a7.5 7.5 0 10-3.338 3.338l.018.028.341 3.077a.75.75 0 001.214.494l2.423-2.022.003-.002a7.567 7.567 0 002.76-5.73 7.5 7.5 0 00-1.393-4.507z"/>
                        </svg>
                        Conversar no WhatsApp
                    </a>
                </div>
            </div>
            @endif

            <div class="bg-indigo-600 dark:bg-indigo-800 rounded-2xl shadow-lg p-8 text-center flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-white mb-4">Pronto para fazer seu pedido?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Entre em contato conosco e receba os melhores produtos de limpeza com entrega rápida e segura.
                </p>
                <div>
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
    </div>
</div>
@endsection


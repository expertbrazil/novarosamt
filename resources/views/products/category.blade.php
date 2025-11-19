@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
        @if($category->description)
        <p class="text-gray-600 mt-2">{{ $category->description }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <a href="{{ route('product.show', $product->id) }}" class="block focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
                
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">{{ $product->description }}</p>
                    <div class="flex flex-col mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}</span>
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
            </a>
            <div class="px-4 pb-4">
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
        @empty
        <div class="col-span-4 text-center py-12">
            <p class="text-gray-500">Nenhum produto encontrado nesta categoria.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection


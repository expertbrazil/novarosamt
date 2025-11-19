@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            @if($product->image)
            <div class="md:w-1/2">
                <img src="{{ $product->image_url }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            </div>
            @endif
            
            <div class="p-6 md:w-{{ $product->image ? '1/2' : 'full' }}">
                @if($product->category)
                <span class="inline-block px-3 py-1 bg-indigo-600 dark:bg-indigo-600 text-white dark:text-white text-sm font-medium rounded-full mb-4">
                    {{ $product->category->name }}
                </span>
                @endif
                
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $product->name }}</h1>
                
                @if($product->description)
                <p class="text-gray-600 dark:text-gray-300 mb-6">{{ $product->description }}</p>
                @endif
                
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Preço de Venda</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Descrição</p>
                    <p class="text-gray-900 dark:text-white">{{ $product->description }}</p>
                </div>
                @endif
                
                <div class="flex gap-3">
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
                           class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-semibold text-center hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Adicionado ao Carrinho ({{ $cartQuantity }})
                        </a>
                    @else
                        <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Adicionar ao Carrinho
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Carrinho de Compras</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($cartItems))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">Seu carrinho está vazio</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Adicione produtos ao carrinho para continuar.</p>
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Continuar Comprando
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($cartItems as $item)
                    <div class="p-6 flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $item['product']->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ $item['product']->category->name ?? 'Sem categoria' }}
                            </p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                R$ {{ number_format($item['product']->sale_price ?? $item['product']->price, 2, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <form method="POST" action="{{ route('cart.update', $item['product']->id) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Qtd:</label>
                                <input type="number" 
                                       name="quantity" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1" 
                                       max="{{ $item['product']->stock }}"
                                       class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                       onchange="this.form.submit()">
                            </form>
                            
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Subtotal</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    R$ {{ number_format($item['subtotal'], 2, ',', '.') }}
                                </p>
                            </div>
                            
                            <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        title="Remover do carrinho">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('home') }}" 
                       class="flex-1 text-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Continuar Comprando
                    </a>
                    <form method="POST" action="{{ route('cart.clear') }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-6 py-3 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900 transition-colors">
                            Limpar Carrinho
                        </button>
                    </form>
                    <a href="{{ route('order.create') }}" 
                       class="flex-1 text-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Finalizar Pedido
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection


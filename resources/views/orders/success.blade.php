@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <!-- Success Icon -->
        <div class="text-center mb-6">
            <div class="mx-auto w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pedido Realizado com Sucesso!</h1>
            <p class="text-gray-600 dark:text-gray-400">Seu pedido #{{ $order->id }} foi registrado e será processado em breve.</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Resumo do Pedido</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cliente</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                @if($order->customer_email)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">E-mail</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_email }}</p>
                </div>
                @endif
                @if($order->customer_phone)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Telefone</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_phone }}</p>
                </div>
                @endif
            </div>

            @if($order->customer_address)
            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Endereço de Entrega</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_address }}</p>
            </div>
            @endif

            <!-- Order Items -->
            @if($order->items->count() > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Itens do Pedido</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Qtd: {{ $item->quantity }} × R$ {{ number_format($item->unit_price, 2, ',', '.') }}</p>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Totals -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                    <span class="font-medium text-gray-900 dark:text-white">R$ {{ number_format($order->items->sum('subtotal'), 2, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between items-center mb-2 text-green-600 dark:text-green-400">
                    <span>Desconto 
                        @if($order->discount_type === 'percent')
                            ({{ $order->discount_value }}%)
                        @endif
                    </span>
                    <span class="font-medium">- R$ {{ number_format($order->discount_amount, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                </div>
            </div>

            @if($order->observations)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Observações</p>
                <p class="text-gray-900 dark:text-white">{{ $order->observations }}</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Continuar Comprando
            </a>
            <a href="{{ route('cart.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Ver Carrinho
            </a>
        </div>

        <!-- Info Message -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-medium mb-1">O que acontece agora?</p>
                    <p>Seu pedido foi registrado com sucesso. Nossa equipe entrará em contato em breve para confirmar os detalhes e agendar a entrega.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


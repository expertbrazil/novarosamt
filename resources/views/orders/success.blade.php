@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Pedido Realizado com Sucesso!</h1>
        <p class="text-gray-600 mb-6">Seu pedido #{{ $order->id }} foi registrado e será processado em breve.</p>
        
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <h2 class="font-semibold mb-3">Resumo do Pedido:</h2>
            <p><strong>Cliente:</strong> {{ $order->customer_name }}</p>
            <p><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ $order->status_label }}</p>
        </div>

        <div class="space-x-4">
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Voltar ao Início
            </a>
            <a href="{{ route('order.create') }}" class="inline-block bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                Novo Pedido
            </a>
        </div>
    </div>
</div>
@endsection


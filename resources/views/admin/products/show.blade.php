@extends('layouts.admin')

@php
    $title = 'Produto: ' . $product->name;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Produtos
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                {{ $product->name }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Detalhes completos do produto
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:ml-4 md:mt-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                <svg class="w-1.5 h-1.5 mr-1.5 {{ $product->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                {{ $product->category->name }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Details -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Informações do Produto
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        @if($product->image)
                            <div class="sm:col-span-2 flex justify-center">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="h-64 w-64 rounded-lg object-cover shadow-lg">
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $product->description ?: 'Sem descrição' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unidade</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($product->unit)
                                    {{ $product->unit_value }} {{ $product->unit }}
                                @else
                                    Não definida
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Informações de Preço
                    </h3>
                    
                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Preço de Custo
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </dd>
                        </div>
                        
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Margem de Lucro
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($product->profit_margin_percent ?? 0, 1, ',', '.') }}%
                            </dd>
                        </div>
                        
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Preço de Venda
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Stock & Purchase Info -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Estoque e Compras
                    </h3>
                    
                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Estoque Atual
                            </dt>
                            <dd class="mt-1 text-2xl font-semibold {{ $product->stock <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $product->stock }}
                            </dd>
                        </div>
                        
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Última Compra (Custo)
                            </dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $product->last_purchase_cost !== null ? 'R$ ' . number_format($product->last_purchase_cost, 2, ',', '.') : 'N/A' }}
                            </dd>
                        </div>
                        
                        <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Data da Última Compra
                            </dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $product->last_purchase_at ? $product->last_purchase_at->format('d/m/Y') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Histórico de Pedidos
                    </h3>
                    
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Pedido
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Quantidade
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Data
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($orders as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('admin.orders.show', $item->order) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    #{{ $item->order->id }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->order->customer_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum pedido</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Este produto ainda não foi incluído em nenhum pedido.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Ações
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar Produto
                        </a>
                        
                        @if($orders->count() == 0)
                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Excluir Produto
                                </button>
                            </form>
                        @else
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Não é possível excluir este produto pois ele possui pedidos associados.
                                </p>
                            </div>
                        @endif
                        
                        <a href="{{ route('admin.products.index') }}" 
                           class="btn-secondary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customers -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Clientes que Compraram
                    </h3>
                    
                    @if($customers->count() > 0)
                        <ul class="space-y-2">
                            @foreach($customers as $customer)
                                <li>
                                    <a href="{{ route('admin.customers.show', $customer) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                        {{ $customer->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Nenhum cliente comprou este produto ainda.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



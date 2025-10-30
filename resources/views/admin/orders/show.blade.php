@extends('layouts.admin')

@php
    $title = 'Pedido #' . $order->id;
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
                            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Pedidos
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">#{{ $order->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Pedido #{{ $order->id }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Criado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:ml-4 md:mt-0">
            @php
                $statusConfig = [
                    'pending' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200', 'Pendente'],
                    'processing' => ['bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', 'Em Processamento'],
                    'completed' => ['bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200', 'Concluído'],
                    'cancelled' => ['bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200', 'Cancelado'],
                ];
                $config = $statusConfig[$order->status] ?? ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', 'Desconhecido'];
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                {{ $config[1] }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Informações do Cliente
                    </h3>
                    
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->customer_name }}</dd>
                        </div>
                        
                        @if($order->customer_email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="mailto:{{ $order->customer_email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    {{ $order->customer_email }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($order->customer_phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $order->customer_phone) }}" 
                                   target="_blank" 
                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    {{ $order->customer_phone }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($order->customer_cpf)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CPF</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $order->customer_cpf }}</dd>
                        </div>
                        @endif
                        
                        @if($order->customer_address)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->customer_address }}</dd>
                        </div>
                        @endif
                        
                        @if($order->payment_method)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Forma de Pagamento</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($order->payment_method) }}</dd>
                        </div>
                        @endif
                        
                        @if($order->due_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Vencimento</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->due_date->format('d/m/Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                    
                    @if($order->observations)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Observações</dt>
                        <dd class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            {{ $order->observations }}
                        </dd>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                        Itens do Pedido
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Quantidade
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Preço Unit.
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($order->items as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($item->product->image)
                                                        <img src="{{ $item->product->image_url }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="h-10 w-10 rounded-lg object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        <a href="{{ route('admin.products.show', $item->product) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $item->product->category->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            R$ {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Total do Pedido:
                                    </th>
                                    <th class="px-6 py-4 text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Gerenciar Status
                    </h3>
                    
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status do Pedido
                            </label>
                            <div class="mt-1">
                                <select name="status" 
                                        id="status"
                                        class="form-input">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Em Processamento</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Concluído</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Atualizar Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Informações do Pedido
                    </h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID do Pedido</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">#{{ $order->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Criação</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Atualização</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        @if($order->whatsapp_sent_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">WhatsApp Enviado</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->whatsapp_sent_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Itens</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->items->sum('quantity') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Ações
                    </h3>
                    
                    <div class="space-y-3">
                        @if($order->customer_phone)
                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $order->customer_phone) }}" 
                           target="_blank"
                           class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            Enviar WhatsApp
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.orders.index') }}" 
                           class="btn-secondary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Voltar aos Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


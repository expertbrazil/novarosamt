@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
    $title = 'Pedidos de Compra';
@endphp

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-100">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Pedidos de Compra</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Centralize solicitações de reposição e acompanhe o andamento com facilidade.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.purchase-orders.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Pedido de Compra
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total de pedidos</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Rascunhos</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['draft'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Enviados</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['sent'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label for="search" class="sr-only">Buscar pedidos</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-input pl-10"
                               placeholder="Buscar por número ou anotações...">
                    </div>
                </div>
                <div class="flex gap-3">
                    <select name="status" class="form-input">
                        <option value="">Todos os status</option>
                        <option value="rascunho" {{ request('status') === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                        <option value="enviado" {{ request('status') === 'enviado' ? 'selected' : '' }}>Enviado</option>
                    </select>
                    <button type="submit" class="btn-primary whitespace-nowrap">Filtrar</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.purchase-orders.index') }}" class="btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($purchaseOrders->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Itens</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($purchaseOrders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        Pedido #{{ $order->id }}
                                    </div>
                                    @if($order->notes)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                            {{ Str::limit($order->notes, 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.purchase-orders.toggle-status', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $order->status_badge }} hover:opacity-80 cursor-pointer"
                                                title="Clique para alternar o status">
                                            <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            {{ $order->status_label }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <div>{{ $order->created_at?->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at?->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $order->creator->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.purchase-orders.show', $order) }}"
                                           class="action-icon"
                                           title="Ver detalhes">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.purchase-orders.edit', $order) }}"
                                           class="action-icon text-blue-600 dark:text-blue-300"
                                           title="Editar pedido">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.purchase-orders.pdf', $order) }}"
                                           class="action-icon text-indigo-600 dark:text-indigo-300"
                                           target="_blank"
                                           title="Baixar PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.654 0 3-1.346 3-3S13.654 5 12 5 9 6.346 9 8s1.346 3 3 3z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 21v-2a4 4 0 014-4h6a4 4 0 014 4v2"/>
                                            </svg>
                                        </a>
                                        <button type="button"
                                                class="action-icon text-purple-600 dark:text-purple-300"
                                                data-email-modal
                                                data-action="{{ route('admin.purchase-orders.send-email', $order) }}"
                                                title="Enviar e-mail ao fornecedor">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 dark:bg-gray-900/70">
                        <tr>
                            <th colspan="2" class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase text-right">
                                Total de itens exibidos
                            </th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                {{ $purchaseOrders->sum('items_count') }}
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($purchaseOrders as $order)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Pedido #{{ $order->id }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                                </p>
                            </div>
                            <form action="{{ route('admin.purchase-orders.toggle-status', $order) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $order->status_badge }} hover:opacity-80">
                                    {{ $order->status_label }}
                                </button>
                            </form>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Criado em {{ $order->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.purchase-orders.show', $order) }}" class="btn-secondary flex-1 text-center text-xs">Detalhes</a>
                            <a href="{{ route('admin.purchase-orders.edit', $order) }}" class="btn-secondary flex-1 text-center text-xs">Editar</a>
                            <button type="button"
                                    class="btn-secondary flex-1 text-center text-xs"
                                    data-email-modal
                                    data-action="{{ route('admin.purchase-orders.send-email', $order) }}">
                                E-mail
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $purchaseOrders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M3 5.25h18M5.25 21h13.5c.621 0 1.125-.504 1.125-1.125V6M5.25 21A2.25 2.25 0 013 18.75V6"/>
                </svg>
                <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Nenhum pedido de compra</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Clique em "Novo Pedido de Compra" para gerar a primeira solicitação.
                </p>
            </div>
        @endif
    </div>
</div>

@include('admin.purchase-orders.partials.send-email-modal')

<style>
    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
    }

    .dark .action-icon {
        background-color: #111827;
        border-color: #374151;
        color: #d1d5db;
        box-shadow: none;
    }

    .action-icon:hover {
        color: #4f46e5;
    }
</style>
@endsection


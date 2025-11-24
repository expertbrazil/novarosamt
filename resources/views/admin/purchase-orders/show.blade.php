@extends('layouts.admin')

@php
    $title = 'Pedido de Compra #' . $purchaseOrder->id;
@endphp

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Pedido de Compra #{{ $purchaseOrder->id }}</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Visualize o andamento e os itens solicitados para reposição.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.purchase-orders.pdf', $purchaseOrder) }}" target="_blank" class="btn-secondary">
                Exportar PDF
            </a>
            <button type="button"
                    class="btn-secondary"
                    data-email-modal
                    data-action="{{ route('admin.purchase-orders.send-email', $purchaseOrder) }}">
                Enviar e-mail
            </button>
            <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}" class="btn-primary">
                Editar pedido
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 sm:py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</dt>
                            <dd class="mt-2">
                                <form action="{{ route('admin.purchase-orders.toggle-status', $purchaseOrder) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $purchaseOrder->status_badge }} hover:opacity-90 transition">
                                        {{ $purchaseOrder->status_label }}
                                        <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Clique para alternar entre rascunho e enviado.
                                </p>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Criado em</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $purchaseOrder->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Responsável</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $purchaseOrder->creator->name ?? '—' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 sm:py-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Itens solicitados</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Quantidades previstas para recompor o estoque.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produto</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qtd.</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($purchaseOrder->items as $index => $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">#{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $item->product->name }}
                                        </div>
                                        @if($item->product->category?->name)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->product->category->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-white">
                                        {{ $item->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 dark:bg-gray-900/60">
                                <th colspan="2" class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                    Total de itens solicitados
                                </th>
                                <th class="px-4 py-3 text-center text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $purchaseOrder->items->sum('quantity') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($purchaseOrder->notes)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 sm:py-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Observações</h2>
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 p-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $purchaseOrder->notes }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 sm:py-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Atalhos</h2>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}" class="btn-secondary w-full text-center">
                            Editar pedido
                        </a>
                        <a href="{{ route('admin.purchase-orders.pdf', $purchaseOrder) }}" target="_blank" class="btn-secondary w-full text-center">
                            Baixar PDF
                        </a>
                        <button type="button"
                                class="btn-secondary w-full text-center"
                                data-email-modal
                                data-action="{{ route('admin.purchase-orders.send-email', $purchaseOrder) }}">
                            Enviar por e-mail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.purchase-orders.partials.send-email-modal')
@endsection


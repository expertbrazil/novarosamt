@extends('layouts.admin')

@php
    $title = 'Novo Pedido de Compra';
@endphp

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="{{ route('admin.purchase-orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Pedidos de Compra
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Novo Pedido de Compra</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Novo Pedido de Compra
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Pré-preenchemos a lista com produtos que estão no estoque mínimo. Ajuste as quantidades ou inclua novos itens conforme necessário.
            </p>
        </div>
    </div>

    @include('admin.purchase-orders.partials.form', [
        'formAction' => route('admin.purchase-orders.store'),
        'method' => 'POST',
        'submitLabel' => 'Criar Pedido de Compra',
        'categories' => $categories,
        'initialItems' => $initialItems,
        'showStatusField' => false,
        'purchaseOrder' => null,
        'formId' => 'purchaseOrderFormCreate',
    ])
</div>
@endsection


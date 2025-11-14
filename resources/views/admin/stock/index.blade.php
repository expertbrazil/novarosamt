<?php /** @var \Illuminate\Pagination\LengthAwarePaginator $movements */ ?>
<?php /** @var \Illuminate\Support\Collection $products */ ?>

@extends('layouts.admin')

@php
    $title = 'Estoque';
@endphp

@section('content')
<style>
    /* Garantir que links sejam clicáveis no mobile */
    @media (max-width: 768px) {
        .stock-movement-card {
            position: relative;
            z-index: 1;
            cursor: pointer;
            -webkit-tap-highlight-color: rgba(79, 70, 229, 0.3);
            touch-action: manipulation;
            min-height: 44px; /* Área mínima de toque recomendada */
        }
        .stock-movement-card:active {
            opacity: 0.8;
            transform: scale(0.98);
        }
    }
</style>
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Movimentações de Estoque</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Gerencie entradas, saídas e ajustes de estoque
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.stock.create') }}" 
               class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Movimentação
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Entries -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Entradas (período)
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['entries'] ?? 0, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Exits -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Saídas (período)
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['exits'] ?? 0, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Saldo Líquido
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['net'] ?? 0, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Estoque Baixo
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ \App\Models\Product::where('stock', '<=', 10)->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <!-- Product Filter -->
                    <div class="sm:col-span-2">
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Produto
                        </label>
                        <div class="mt-1">
                            <select name="product_id" id="product_id" class="form-input">
                                <option value="">Todos os produtos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ ($filters['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo
                        </label>
                        <div class="mt-1">
                            <select name="type" id="type" class="form-input">
                                <option value="">Todos os tipos</option>
                                @foreach(['in' => 'Entrada', 'out' => 'Saída', 'adjustment_in' => 'Ajuste Entrada', 'adjustment_out' => 'Ajuste Saída'] as $k => $v)
                                    <option value="{{ $k }}" {{ ($filters['type'] ?? '') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Data Inicial
                        </label>
                        <div class="mt-1">
                            <input type="date" 
                                   name="from" 
                                   id="from"
                                   value="{{ $filters['from'] ?? '' }}" 
                                   class="form-input">
                        </div>
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Data Final
                        </label>
                        <div class="mt-1">
                            <input type="date" 
                                   name="to" 
                                   id="to"
                                   value="{{ $filters['to'] ?? '' }}" 
                                   class="form-input">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    @if(array_filter($filters))
                        <a href="{{ route('admin.stock.index') }}" class="btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpar Filtros
                        </a>
                    @endif
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Selected Product Info -->
    @if(($stats['product'] ?? null))
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Produto Selecionado
                </h3>
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Produto
                                </div>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $stats['product']->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Estoque Atual
                                </div>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($stats['stock'] ?? 0, 0, ',', '.') }} unidades
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Último Custo
                                </div>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ ($stats['last_purchase_cost'] ?? null) !== null ? 'R$ ' . number_format($stats['last_purchase_cost'], 2, ',', '.') : 'Não informado' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Movements Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($movements->count() > 0)
            <!-- Mobile View -->
            <div class="block md:hidden">
                @foreach($movements as $movement)
                    @php 
                        $labels = [
                            'in' => 'Entrada', 
                            'out' => 'Saída', 
                            'adjustment_in' => 'Ajuste Entrada', 
                            'adjustment_out' => 'Ajuste Saída'
                        ];
                        $badgeClasses = match($movement->type) {
                            'in' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'out' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            'adjustment_in' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'adjustment_out' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                        };
                        $balance = $balanceMap[$movement->id] ?? 0;
                        $currentStock = $movement->product->stock ?? 0;
                        $isLowStock = $movement->product && $movement->product->min_stock && $currentStock <= $movement->product->min_stock;
                    @endphp
                    @if($movement->product)
                    <a href="{{ route('admin.products.show', $movement->product) }}" 
                       class="stock-movement-card block border-b border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 active:bg-gray-100 dark:active:bg-gray-600 transition-colors"
                       style="touch-action: manipulation; -webkit-tap-highlight-color: rgba(79, 70, 229, 0.3); display: block; position: relative; z-index: 1;">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $movement->product->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            SKU: {{ $movement->product->sku ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }} ml-2 flex-shrink-0">
                                {{ $labels[$movement->type] ?? $movement->type }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Data/Hora</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $movement->moved_at?->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $movement->moved_at?->format('H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Quantidade</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($movement->quantity, 0, ',', '.') }} un
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Estoque Atual</p>
                                <p class="font-medium {{ $isLowStock ? 'text-yellow-600 dark:text-yellow-400' : ($currentStock <= 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white') }}">
                                    {{ number_format($currentStock, 0, ',', '.') }}
                                </p>
                            </div>
                            @if($movement->unit_cost !== null)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Custo Unit.</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    R$ {{ number_format($movement->unit_cost, 2, ',', '.') }}
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        @if($movement->reason)
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Motivo</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $movement->reason }}</p>
                        </div>
                        @endif
                    </a>
                    @else
                    <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            Produto removido
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            SKU: N/A
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }} ml-2 flex-shrink-0">
                                {{ $labels[$movement->type] ?? $movement->type }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Data/Hora</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $movement->moved_at?->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $movement->moved_at?->format('H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Quantidade</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($movement->quantity, 0, ',', '.') }} un
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            
            <!-- Desktop View -->
            <div class="overflow-x-auto">
                <style>
                    @media (min-width: 768px) {
                        .stock-desktop-table {
                            display: block !important;
                        }
                    }
                    @media (max-width: 767px) {
                        .stock-desktop-table {
                            display: none !important;
                        }
                    }
                </style>
                <div class="stock-desktop-table">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Data/Hora
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produto
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantidade
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Saldo
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Disponível
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Custo Unitário
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Motivo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Usuário
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php 
                            $labels = [
                                'in' => 'Entrada', 
                                'out' => 'Saída', 
                                'adjustment_in' => 'Ajuste Entrada', 
                                'adjustment_out' => 'Ajuste Saída'
                            ]; 
                        @endphp
                        @foreach($movements as $movement)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $movement->moved_at?->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->moved_at?->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movement->product)
                                    <a href="{{ route('admin.products.show', $movement->product) }}" 
                                       class="flex items-center hover:opacity-80 transition-opacity cursor-pointer group">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900 transition-colors">
                                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $movement->product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                SKU: {{ $movement->product->sku ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </a>
                                    @else
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Produto removido
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                SKU: N/A
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php 
                                        $badgeClasses = match($movement->type) {
                                            'in' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'out' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'adjustment_in' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'adjustment_out' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                        {{ $labels[$movement->type] ?? $movement->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($movement->quantity, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        unidades
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @php
                                        $balance = $balanceMap[$movement->id] ?? 0;
                                    @endphp
                                    <div class="text-sm font-medium {{ $balance >= 0 ? 'text-gray-900 dark:text-white' : 'text-red-600 dark:text-red-400' }}">
                                        {{ number_format($balance, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        acumulado
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @php
                                        $currentStock = $movement->product->stock ?? 0;
                                        $isLowStock = $movement->product && $movement->product->min_stock && $currentStock <= $movement->product->min_stock;
                                    @endphp
                                    <div class="text-sm font-medium {{ $isLowStock ? 'text-yellow-600 dark:text-yellow-400' : ($currentStock <= 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white') }}">
                                        {{ number_format($currentStock, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($movement->product && $movement->product->min_stock)
                                            Mín: {{ $movement->product->min_stock }}
                                        @else
                                            atual
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($movement->unit_cost !== null)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            R$ {{ number_format($movement->unit_cost, 2, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Total: R$ {{ number_format($movement->unit_cost * $movement->quantity, 2, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate" title="{{ $movement->reason }}">
                                        {{ $movement->reason ?: 'Não informado' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6">
                                            <div class="h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <svg class="h-3 w-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $movement->user?->name ?? 'Sistema' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma movimentação encontrada</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(array_filter($filters))
                        Tente ajustar os filtros ou criar uma nova movimentação.
                    @else
                        Comece registrando sua primeira movimentação de estoque.
                    @endif
                </p>
                <div class="mt-6">
                    @if(array_filter($filters))
                        <a href="{{ route('admin.stock.index') }}" class="btn-secondary mr-3">
                            Limpar Filtros
                        </a>
                    @endif
                    <a href="{{ route('admin.stock.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nova Movimentação
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($movements->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 rounded-lg shadow">
            {{ $movements->links() }}
        </div>
    @endif
</div>
@endsection



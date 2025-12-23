@extends('layouts.admin')

@php
    $title = 'Clientes';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Clientes</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Gerencie os clientes do sistema
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.customers.create') }}" 
               class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Cliente
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Customers -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total de Clientes
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $totalCustomers }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Customers -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Clientes Ativos
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $activeCustomers }}
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
                    <!-- Search -->
                    <div class="sm:col-span-2">
                        <label for="search" class="sr-only">Buscar clientes</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}" 
                                   class="form-input pl-10" 
                                   placeholder="Buscar por nome, email, CPF ou telefone...">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="sr-only">Filtrar por status</label>
                        <select name="status" id="status" class="form-input">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>

                    <!-- Mês de Aniversário -->
                    <div>
                        <label for="birthday_month" class="sr-only">Mês de Aniversário</label>
                        <select name="birthday_month" id="birthday_month" class="form-input">
                            <option value="">Todos os meses</option>
                            <option value="1" {{ request('birthday_month') == '1' ? 'selected' : '' }}>Janeiro</option>
                            <option value="2" {{ request('birthday_month') == '2' ? 'selected' : '' }}>Fevereiro</option>
                            <option value="3" {{ request('birthday_month') == '3' ? 'selected' : '' }}>Março</option>
                            <option value="4" {{ request('birthday_month') == '4' ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ request('birthday_month') == '5' ? 'selected' : '' }}>Maio</option>
                            <option value="6" {{ request('birthday_month') == '6' ? 'selected' : '' }}>Junho</option>
                            <option value="7" {{ request('birthday_month') == '7' ? 'selected' : '' }}>Julho</option>
                            <option value="8" {{ request('birthday_month') == '8' ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ request('birthday_month') == '9' ? 'selected' : '' }}>Setembro</option>
                            <option value="10" {{ request('birthday_month') == '10' ? 'selected' : '' }}>Outubro</option>
                            <option value="11" {{ request('birthday_month') == '11' ? 'selected' : '' }}>Novembro</option>
                            <option value="12" {{ request('birthday_month') == '12' ? 'selected' : '' }}>Dezembro</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button type="submit" class="btn-primary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                            </svg>
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'status', 'birthday_month']))
                            <a href="{{ route('admin.customers.index') }}" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpar
                            </a>
                        @endif
                    </div>
                </div>
                
                @if(request()->filled('birthday_month'))
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                onclick="startBulkBirthdayMessages({{ request('birthday_month') }})"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                            </svg>
                            Enviar Mensagens de Parabéns
                        </button>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Enviará mensagens via WhatsApp para todos os aniversariantes deste mês que possuem telefone cadastrado e estão ativos. Cada mensagem será enviada com intervalo de 20 segundos.
                        </p>
                    </div>
                @endif
                </div>
            </form>
        </div>
    </div> 
   <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($customers->count() > 0)
            <!-- Desktop Table -->
            <div class="desktop-customers-table overflow-x-auto" style="display: none;">
                <style>
                    @media (min-width: 768px) {
                        .desktop-customers-table {
                            display: block !important;
                        }
                    }
                </style>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Contato
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Informações
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pedidos
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Ações</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $customer->name }}
                                            </div>
                                            @if($customer->cpf)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    CPF: {{ $customer->cpf }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ $customer->email }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">Sem email</span>
                                        @endif
                                    </div>
                                    @if($customer->phone)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $customer->phone) }}" 
                                               target="_blank" 
                                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                                </svg>
                                                {{ $customer->phone }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($customer->birth_date)
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $customer->birth_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($customer->birth_date)->age }} anos
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Não informado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $customer->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                                                title="Clique para {{ $customer->is_active ? 'inativar' : 'ativar' }}">
                                            <svg class="w-1.5 h-1.5 mr-1.5 {{ $customer->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            {{ $customer->is_active ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $customer->orders_count }} {{ Str::plural('pedido', $customer->orders_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.customers.show', $customer) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 shadow-sm transition-colors duration-200"
                                           title="Ver detalhes"
                                           aria-label="Ver detalhes">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($customer->is_active)
                                            <a href="{{ route('admin.orders.create', ['customer_id' => $customer->id]) }}" 
                                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-green-600 hover:text-green-900 dark:text-green-300 dark:hover:text-green-100 shadow-sm transition-colors duration-200"
                                               title="Novo pedido"
                                               aria-label="Novo pedido">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        @if(request()->filled('birthday_month') && $customer->phone && $customer->birth_date)
                                            <button type="button" 
                                                    onclick="previewBirthdayMessage({{ $customer->id }}, '{{ $customer->name }}')"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-purple-600 hover:text-purple-900 dark:text-purple-300 dark:hover:text-purple-100 shadow-sm transition-colors duration-200"
                                                    title="Enviar mensagem de parabéns"
                                                    aria-label="Enviar mensagem de parabéns">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-blue-600 hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-100 shadow-sm transition-colors duration-200"
                                           title="Editar cliente"
                                           aria-label="Editar cliente">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($customer->orders_count == 0)
                                            <form method="POST" 
                                                  action="{{ route('admin.customers.destroy', $customer) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-red-600 hover:text-red-900 dark:text-red-300 dark:hover:text-red-100 shadow-sm transition-colors duration-200"
                                                        title="Excluir cliente"
                                                        aria-label="Excluir cliente">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-300 dark:text-gray-600 shadow-sm" title="Não é possível excluir: cliente possui pedidos">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($customers as $customer)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center flex-1">
                                <div class="flex-shrink-0 h-11 w-11">
                                    <div class="h-11 w-11 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $customer->name }}
                                    </div>
                                    @if($customer->cpf)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                            CPF: {{ $customer->cpf }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $customer->orders_count }} {{ Str::plural('pedido', $customer->orders_count) }}
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="ml-3">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $customer->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                                        title="Clique para {{ $customer->is_active ? 'inativar' : 'ativar' }}">
                                    <svg class="w-1.5 h-1.5 mr-1.5 {{ $customer->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    {{ $customer->is_active ? 'Ativo' : 'Inativo' }}
                                </button>
                            </form>
                        </div>

                        <div class="space-y-3 mb-3">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Contato</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($customer->email)
                                        <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 break-all">
                                            {{ $customer->email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Sem email</span>
                                    @endif
                                </div>
                                @if($customer->phone)
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $customer->phone) }}" 
                                           target="_blank" 
                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                            </svg>
                                            {{ $customer->phone }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Nascimento</div>
                                    @if($customer->birth_date)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $customer->birth_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($customer->birth_date)->age }} anos
                                        </div>
                                    @else
                                        <span class="text-gray-400">Não informado</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Pedidos</div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $customer->orders_count }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.customers.show', $customer) }}" 
                               class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100 shadow-sm transition-colors duration-200"
                               title="Ver detalhes">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detalhes
                            </a>

                            @if(request()->filled('birthday_month') && $customer->phone && $customer->birth_date)
                                <button type="button" 
                                        onclick="previewBirthdayMessage({{ $customer->id }}, '{{ $customer->name }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-purple-600 hover:text-purple-900 dark:text-purple-300 dark:hover:text-purple-100 shadow-sm transition-colors duration-200"
                                        title="Enviar mensagem de parabéns">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                                    </svg>
                                    Parabéns
                                </button>
                            @endif

                            <a href="{{ route('admin.customers.edit', $customer) }}" 
                               class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-blue-600 hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-100 shadow-sm transition-colors duration-200"
                               title="Editar cliente">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>

                            @if($customer->is_active)
                                <a href="{{ route('admin.orders.create', ['customer_id' => $customer->id]) }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-green-600 hover:text-green-900 dark:text-green-300 dark:hover:text-green-100 shadow-sm transition-colors duration-200"
                                   title="Novo pedido">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Novo Pedido
                                </a>
                            @endif

                            @if($customer->phone)
                                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $customer->phone) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-green-600 hover:text-green-900 dark:text-green-300 dark:hover:text-green-100 shadow-sm transition-colors duration-200"
                                   title="Conversar no WhatsApp">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </a>
                            @endif

                            @if($customer->orders_count == 0)
                                <form method="POST" 
                                      action="{{ route('admin.customers.destroy', $customer) }}" 
                                      class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-300 dark:hover:text-red-100 shadow-sm transition-colors duration-200"
                                            title="Excluir cliente">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Excluir
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-400 dark:text-gray-500 shadow-sm" title="Não é possível excluir: cliente possui pedidos">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Não removível
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum cliente encontrado</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(request()->hasAny(['search', 'status', 'birthday_month']))
                        Tente ajustar os filtros ou criar um novo cliente.
                    @else
                        Comece criando seu primeiro cliente.
                    @endif
                </p>
                <div class="mt-6">
                    @if(request()->hasAny(['search', 'status', 'birthday_month']))
                        <a href="{{ route('admin.customers.index') }}" class="btn-secondary mr-3">
                            Limpar Filtros
                        </a>
                    @endif
                    <a href="{{ route('admin.customers.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Novo Cliente
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 rounded-lg shadow">
            {{ $customers->links() }}
        </div>
    @endif
</div>

<!-- Modal de Preview da Mensagem de Aniversário -->
<div id="birthdayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Mensagem de Parabéns
                </h3>
                <button onclick="closeBirthdayModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <strong>Cliente:</strong> <span id="modalCustomerName" class="text-gray-900 dark:text-white"></span>
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Telefone:</strong> <span id="modalCustomerPhone" class="text-gray-900 dark:text-white"></span>
                </p>
            </div>
            
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview da mensagem:</p>
                <textarea id="modalMessagePreview" 
                          rows="8"
                          class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 whitespace-pre-wrap font-mono text-sm resize-y" 
                          style="background-color: #ffffff; color: #000000; border-color: #d1d5db;"></textarea>
                <style>
                    #modalMessagePreview {
                        background-color: #ffffff !important;
                        color: #000000 !important;
                        border-color: #d1d5db !important;
                    }
                    .dark #modalMessagePreview,
                    .dark #modalMessagePreview:focus {
                        background-color: #1f2937 !important;
                        color: #ffffff !important;
                        border-color: #4b5563 !important;
                    }
                </style>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeBirthdayModal()" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <form id="sendBirthdayForm" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Enviar Mensagem
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewBirthdayMessage(customerId, customerName) {
    // Mostrar loading
    document.getElementById('modalCustomerName').textContent = customerName;
    document.getElementById('modalCustomerPhone').textContent = 'Carregando...';
    document.getElementById('modalMessagePreview').textContent = 'Carregando mensagem...';
    document.getElementById('birthdayModal').classList.remove('hidden');
    
    // Buscar preview da mensagem
    fetch(`/admin/customers/${customerId}/preview-birthday-message`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalCustomerPhone').textContent = data.phone || 'Não informado';
                document.getElementById('modalMessagePreview').value = data.message;
                
                // Configurar formulário de envio
                const form = document.getElementById('sendBirthdayForm');
                form.action = `/admin/customers/${customerId}/send-birthday-message`;
                
                // Adicionar campo hidden com a mensagem editada
                let messageInput = form.querySelector('input[name="message"]');
                if (!messageInput) {
                    messageInput = document.createElement('input');
                    messageInput.type = 'hidden';
                    messageInput.name = 'message';
                    form.appendChild(messageInput);
                }
                
                // Atualizar mensagem quando o usuário editar
                document.getElementById('modalMessagePreview').addEventListener('input', function() {
                    messageInput.value = this.value;
                });
                messageInput.value = data.message;
            } else {
                alert('Erro ao carregar mensagem: ' + (data.error || 'Erro desconhecido'));
                closeBirthdayModal();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar mensagem. Tente novamente.');
            closeBirthdayModal();
        });
}

function closeBirthdayModal() {
    document.getElementById('birthdayModal').classList.add('hidden');
}

// Modal de Progresso do Envio em Massa
let progressInterval = null;

function startBulkBirthdayMessages(month) {
    if (!confirm('Deseja enviar mensagens de parabéns para todos os aniversariantes deste mês que possuem telefone cadastrado? Cada mensagem será enviada com intervalo de 20 segundos.')) {
        return;
    }
    
    // Mostrar modal de progresso
    document.getElementById('bulkProgressModal').classList.remove('hidden');
    document.getElementById('bulkProgressBar').style.width = '0%';
    document.getElementById('bulkProgressBar').textContent = '0%';
    document.getElementById('bulkProgressText').textContent = 'Iniciando...';
    document.getElementById('bulkProgressDetails').textContent = '';
    
    // Iniciar polling do progresso imediatamente
    startProgressPolling(month);
    
    // Iniciar envio (processamento assíncrono)
    fetch(`{{ route('admin.customers.send-birthday-messages') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ month: month })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'Erro ao iniciar envio');
            closeBulkProgressModal();
            return;
        }
        // Polling já está ativo, não precisa fazer nada aqui
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao iniciar envio de mensagens');
        closeBulkProgressModal();
    });
}

function startProgressPolling(month) {
    if (progressInterval) {
        clearInterval(progressInterval);
    }
    
    // Primeira verificação imediata
    checkProgress(month);
    
    // Depois verificar a cada segundo
    progressInterval = setInterval(() => {
        checkProgress(month);
    }, 1000);
}

function checkProgress(month) {
    fetch(`{{ route('admin.customers.birthday-messages-progress') }}?month=${month}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'not_started') {
                // Se ainda não iniciou, aguardar um pouco mais
                return;
            }
            
            const total = data.total || 1;
            const processed = data.processed || 0;
            const success = data.success || 0;
            const errors = data.errors || 0;
            const percentage = Math.min(100, Math.round((processed / total) * 100));
            
            // Atualizar barra de progresso
            document.getElementById('bulkProgressBar').style.width = percentage + '%';
            document.getElementById('bulkProgressBar').textContent = percentage + '%';
            document.getElementById('bulkProgressPercent').textContent = percentage + '%';
            
            // Atualizar texto
            let statusText = `Processando: ${processed} de ${total} mensagens`;
            if (data.current) {
                statusText += ` - ${data.current}`;
            }
            document.getElementById('bulkProgressText').textContent = statusText;
            
            // Atualizar detalhes
            let details = `✓ Sucesso: ${success} | ✗ Erros: ${errors}`;
            if (data.current) {
                details += `\n📤 ${data.current}`;
            }
            if (data.message && data.message !== 'Processando...') {
                details += `\n\n${data.message}`;
            }
            document.getElementById('bulkProgressDetails').textContent = details;
            
            // Se concluído
            if (data.status === 'completed') {
                if (progressInterval) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
                document.getElementById('bulkProgressText').textContent = data.message || 'Concluído!';
                document.getElementById('bulkProgressBar').classList.remove('bg-blue-600');
                document.getElementById('bulkProgressBar').classList.add('bg-green-600');
                
                // Mostrar erros se houver
                if (data.errors_list && data.errors_list.length > 0) {
                    let errorsText = 'Erros encontrados:\n' + data.errors_list.slice(0, 5).join('\n');
                    if (data.errors_list.length > 5) {
                        errorsText += `\n... e mais ${data.errors_list.length - 5} erro(s)`;
                    }
                    document.getElementById('bulkProgressDetails').textContent = details + '\n\n' + errorsText;
                }
                
                // Fechar automaticamente após 10 segundos
                setTimeout(() => {
                    closeBulkProgressModal();
                    location.reload();
                }, 10000);
            }
        })
        .catch(error => {
            console.error('Erro ao buscar progresso:', error);
        });
}

function closeBulkProgressModal() {
    if (progressInterval) {
        clearInterval(progressInterval);
        progressInterval = null;
    }
    document.getElementById('bulkProgressModal').classList.add('hidden');
    document.getElementById('bulkProgressBar').classList.remove('bg-green-600');
    document.getElementById('bulkProgressBar').classList.add('bg-blue-600');
    document.getElementById('bulkProgressBar').style.width = '0%';
    document.getElementById('bulkProgressBar').textContent = '0%';
}
</script>

<!-- Modal de Progresso do Envio em Massa -->
<div id="bulkProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Enviando Mensagens de Parabéns
                </h3>
                <button onclick="closeBulkProgressModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Barra de Progresso -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span id="bulkProgressText" class="text-sm font-medium text-gray-700 dark:text-gray-300">Iniciando...</span>
                    <span id="bulkProgressPercent" class="text-sm font-medium text-gray-700 dark:text-gray-300">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                    <div id="bulkProgressBar" 
                         class="bg-blue-600 h-4 rounded-full transition-all duration-300 text-white text-xs flex items-center justify-center"
                         style="width: 0%; min-width: 0%;">
                        0%
                    </div>
                </div>
            </div>
            
            <!-- Detalhes -->
            <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                <pre id="bulkProgressDetails" class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono"></pre>
            </div>
            
            <div class="flex justify-end">
                <button onclick="closeBulkProgressModal()" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
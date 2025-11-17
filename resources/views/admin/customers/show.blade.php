@extends('layouts.admin')

@php
    $title = 'Cliente: ' . $customer->name;
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Dados do Cliente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <p><strong>Status:</strong>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $customer->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </p>
                <p><strong>Tipo de Pessoa:</strong> {{ $customer->person_type === 'PJ' ? 'Jurídica' : 'Física' }}</p>
                <p><strong>Nome:</strong> {{ $customer->name }}</p>
                @if($customer->person_type === 'PJ')
                    <p><strong>CNPJ:</strong> {{ $customer->cnpj }}</p>
                @else
                    <p><strong>CPF:</strong> {{ $customer->cpf }}</p>
                    @if($customer->birth_date)
                        <p><strong>Data de Nascimento:</strong> {{ $customer->birth_date->format('d/m/Y') }}</p>
                    @endif
                @endif
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Telefone:</strong>
                    @if($customer->phone)
                        <a href="https://wa.me/55{{ preg_replace('/\D/','',$customer->phone) }}" target="_blank" class="text-green-700 hover:text-green-900">{{ $customer->phone }}</a>
                    @endif
                </p>
                <p><strong>CEP:</strong> {{ $customer->cep }}</p>
                <p><strong>Logradouro:</strong> {{ $customer->street }}</p>
                <p><strong>Número:</strong> {{ $customer->number }}</p>
                <p><strong>Complemento:</strong> {{ $customer->complement }}</p>
                <p><strong>Bairro:</strong> {{ $customer->district }}</p>
                <p><strong>Cidade/UF:</strong> {{ $customer->city }} / {{ $customer->state }}</p>
                <p class="md:col-span-2"><strong>Endereço (completo):</strong> {{ $customer->address }}</p>
                @if($customer->notes)
                <p class="md:col-span-2"><strong>Observações:</strong> {{ $customer->notes }}</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pedidos</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($customer->orders as $order)
                        <tr>
                            <td class="px-4 py-2">#{{ $order->id }}</td>
                            <td class="px-4 py-2">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                       title="Ver detalhes do pedido">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.orders.pdf', $order) }}" 
                                       target="_blank"
                                       class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                       title="Ver PDF do pedido">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">Nenhum pedido para este cliente.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Ações</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Editar</a>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return {{ $customer->orders_count > 0 ? 'false' : 'confirm(\'Tem certeza?\')' }};">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-center bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 {{ $customer->orders_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}" {{ $customer->orders_count > 0 ? 'disabled' : '' }}>
                        Excluir
                    </button>
                </form>
                <a href="{{ route('admin.customers.index') }}" class="block text-center border py-2 rounded-lg hover:bg-gray-50">Voltar</a>
            </div>
        </div>
    </div>
</div>
@endsection



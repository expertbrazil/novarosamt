@extends('layouts.admin')

@php
    $title = 'Clientes - Teste Simples';
@endphp

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        Clientes - Versão Simples
    </h1>
    
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <p class="mb-4">Total de clientes: {{ $customers->total() }}</p>
        
        @if($customers->count() > 0)
            <div class="space-y-4">
                @foreach($customers as $customer)
                    <div class="border-b pb-4">
                        <h3 class="font-medium">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-600">Email: {{ $customer->email ?? 'Não informado' }}</p>
                        <p class="text-sm text-gray-600">CPF: {{ $customer->cpf ?? 'Não informado' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>Nenhum cliente encontrado.</p>
        @endif
    </div>
</div>
@endsection
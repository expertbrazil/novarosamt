@extends('layouts.admin')

@php
    $title = 'Teste - Novo Cliente';
@endphp

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        Teste - Página de Criação de Cliente
    </h1>
    
    <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <p class="text-gray-700 dark:text-gray-300">
            Se você está vendo esta mensagem, o layout está funcionando corretamente.
        </p>
        
        <form method="POST" action="{{ route('admin.customers.store') }}" class="mt-6 space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nome *
                </label>
                <input type="text" 
                       name="name" 
                       id="name"
                       required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>
                <input type="email" 
                       name="email" 
                       id="email"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.customers.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@php
    $title = 'Melhorias e Atualizações';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Melhorias e Atualizações</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Fique por dentro das últimas melhorias e atualizações do sistema
            </p>
        </div>
    </div>

    <!-- Melhorias -->
    <div class="space-y-6">
        @foreach($improvements as $improvement)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $improvement['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $improvement['title'] }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    {{ $improvement['category'] }}
                                </span>
                                @if($improvement['status'] === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    Ativo
                                </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $improvement['description'] }}
                            </p>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Versão {{ $improvement['version'] }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ \Carbon\Carbon::parse($improvement['date'])->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Principais funcionalidades:
                </h4>
                <ul class="space-y-2">
                    @foreach($improvement['features'] as $feature)
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Informação adicional -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                    Mantenha-se atualizado
                </h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p>
                        Esta página é atualizada sempre que novas melhorias são implementadas no sistema. 
                        Verifique periodicamente para estar sempre informado sobre as novidades.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


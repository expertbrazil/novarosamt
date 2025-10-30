@extends('layouts.public')

@section('content')
<!-- Component Examples -->
<div class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Componentes UI Modernos
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Exemplos dos novos componentes com Tailwind CSS
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Card Exemplo 1
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Este é um exemplo de card com o novo design system.
                </p>
                <button class="btn-primary">
                    Ação Principal
                </button>
            </div>
            
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Card Exemplo 2
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Outro exemplo com botão secundário.
                </p>
                <button class="btn-secondary">
                    Ação Secundária
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
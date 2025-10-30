@extends('layouts.admin')

@php
    $title = 'Editar Categoria';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Categorias
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Editar Categoria
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Atualize as informações da categoria "{{ $category->name }}"
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                <svg class="w-1.5 h-1.5 mr-1.5 {{ $category->is_active ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                {{ $category->is_active ? 'Ativa' : 'Inativa' }}
            </span>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Existem {{ $errors->count() }} erro(s) no formulário:
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Nome -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome da Categoria *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name', $category->name) }}" 
                                   class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="Ex: Produtos de Limpeza"
                                   required
                                   autofocus>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Slug atual: <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">{{ $category->slug }}</code>
                        </p>
                    </div>

                    <!-- Descrição -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Descrição
                        </label>
                        <div class="mt-1">
                            <textarea name="description" 
                                      id="description"
                                      rows="4" 
                                      class="form-input @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                      placeholder="Descreva brevemente esta categoria...">{{ old('description', $category->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Opcional. Ajuda os usuários a entenderem o tipo de produtos desta categoria
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active"
                                       value="1" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">
                                    Categoria ativa
                                </label>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Categorias ativas ficam visíveis no sistema e podem receber produtos
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Stats -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                Estatísticas da Categoria
            </h3>
            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        Total de Produtos
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ $category->products_count ?? 0 }}
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        Produtos Ativos
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ $category->active_products_count ?? 0 }}
                    </dd>
                </div>
                <div class="px-4 py-5 bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        Criada em
                    </dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $category->created_at->format('d/m/Y') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Danger Zone -->
    @if($category->products_count == 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-red-200 dark:border-red-800">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-red-900 dark:text-red-200 mb-2">
                Zona de Perigo
            </h3>
            <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                Esta categoria não possui produtos associados e pode ser excluída permanentemente.
            </p>
            <form method="POST" 
                  action="{{ route('admin.categories.destroy', $category) }}" 
                  onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Excluir Categoria
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection



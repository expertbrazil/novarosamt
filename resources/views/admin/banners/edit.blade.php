@extends('layouts.admin')

@php
    $title = 'Editar Banner';
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
                            <a href="{{ route('admin.banners.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Banners
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Editar Banner</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Editar Banner
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Atualize as informações do banner
            </p>
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

            <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Título -->
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Título do Banner *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   value="{{ old('title', $banner->title) }}" 
                                   class="form-input @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="Ex: Promoção de Verão"
                                   required
                                   autofocus>
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagem Desktop -->
                    <div>
                        <label for="image_desktop" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Imagem Desktop
                        </label>
                        @if($banner->image_desktop)
                            <div class="mt-2 mb-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Imagem atual:</p>
                                <img src="{{ asset('storage/' . $banner->image_desktop) }}" 
                                     alt="{{ $banner->title }}" 
                                     class="max-w-full h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                            </div>
                        @endif
                        <div class="mt-1">
                            <input type="file" 
                                   name="image_desktop" 
                                   id="image_desktop"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="form-input @error('image_desktop') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   onchange="previewImage(this, 'desktop-preview')">
                        </div>
                        @error('image_desktop')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $banner->image_desktop ? 'Envie uma nova imagem para substituir' : 'Imagem para exibição em telas desktop (recomendado: 1920x600px)' }}
                        </p>
                        <div id="desktop-preview" class="mt-2 hidden">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Nova imagem:</p>
                            <img id="desktop-preview-img" src="" alt="Preview" class="max-w-full h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                        </div>
                    </div>

                    <!-- Imagem Mobile -->
                    <div>
                        <label for="image_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Imagem Mobile
                        </label>
                        @if($banner->image_mobile)
                            <div class="mt-2 mb-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Imagem atual:</p>
                                <img src="{{ asset('storage/' . $banner->image_mobile) }}" 
                                     alt="{{ $banner->title }}" 
                                     class="max-w-full h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                            </div>
                        @endif
                        <div class="mt-1">
                            <input type="file" 
                                   name="image_mobile" 
                                   id="image_mobile"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="form-input @error('image_mobile') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   onchange="previewImage(this, 'mobile-preview')">
                        </div>
                        @error('image_mobile')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $banner->image_mobile ? 'Envie uma nova imagem para substituir' : 'Imagem para exibição em dispositivos móveis (recomendado: 768x400px)' }}
                        </p>
                        <div id="mobile-preview" class="mt-2 hidden">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Nova imagem:</p>
                            <img id="mobile-preview-img" src="" alt="Preview" class="max-w-full h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                        </div>
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
                                       {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">
                                    Banner ativo
                                </label>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Banners ativos ficam visíveis no site
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.banners.index') }}" 
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
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewId + '-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection


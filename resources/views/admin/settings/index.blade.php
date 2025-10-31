@extends('layouts.admin')

@php
    $title = 'Parâmetros do Sistema';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Parâmetros do Sistema</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Configure as principais configurações da aplicação
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
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

    <!-- Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Logomarca -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Logomarca
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Envie a logomarca da empresa
                    </p>
                </div>

                <div class="space-y-4">
                    @if(isset($settings['logo']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Logo Atual
                            </label>
                            <img src="{{ Storage::url($settings['logo']) }}" 
                                 alt="Logo" 
                                 class="h-32 w-auto object-contain border border-gray-300 dark:border-gray-600 rounded-lg p-2">
                        </div>
                    @endif

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ isset($settings['logo']) ? 'Alterar Logo' : 'Enviar Logo' }}
                        </label>
                        <div class="mt-1">
                            <input type="file" 
                                   name="logo" 
                                   id="logo"
                                   accept="image/*"
                                   class="form-input @error('logo') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Formatos aceitos: JPG, PNG, SVG. Tamanho máximo: 2MB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Configurações do WhatsApp
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Configure as informações do WhatsApp para contato
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Número do WhatsApp
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="whatsapp_number" 
                                   id="whatsapp_number"
                                   value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" 
                                   class="form-input @error('whatsapp_number') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="5511999999999">
                        </div>
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Número com código do país (ex: 5511999999999)
                        </p>
                    </div>

                    <div>
                        <label for="whatsapp_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mensagem Padrão
                        </label>
                        <div class="mt-1">
                            <textarea name="whatsapp_message" 
                                      id="whatsapp_message"
                                      rows="3"
                                      class="form-input @error('whatsapp_message') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                      placeholder="Olá! Gostaria de mais informações sobre...">{{ old('whatsapp_message', $settings['whatsapp_message'] ?? '') }}</textarea>
                        </div>
                        @error('whatsapp_message')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Mensagem padrão que será enviada quando o cliente entrar em contato
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMTP -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Configurações de Email (SMTP)
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Configure o servidor SMTP para envio de emails
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Host SMTP
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="smtp_host" 
                                       id="smtp_host"
                                       value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" 
                                       class="form-input @error('smtp_host') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       placeholder="smtp.gmail.com">
                            </div>
                            @error('smtp_host')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Porta SMTP
                            </label>
                            <div class="mt-1">
                                <input type="number" 
                                       name="smtp_port" 
                                       id="smtp_port"
                                       value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}" 
                                       class="form-input @error('smtp_port') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       placeholder="587">
                            </div>
                            @error('smtp_port')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Criptografia
                        </label>
                        <div class="mt-1">
                            <select name="smtp_encryption" 
                                    id="smtp_encryption"
                                    class="form-input @error('smtp_encryption') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ $settings['smtp_encryption'] ?? 'Selecione...' }}</option>
                                <option value="tls" {{ old('smtp_encryption', $settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption', $settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        @error('smtp_encryption')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Usuário SMTP
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="smtp_username" 
                                   id="smtp_username"
                                   value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" 
                                   class="form-input @error('smtp_username') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="email@exemplo.com">
                        </div>
                        @error('smtp_username')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Senha SMTP
                        </label>
                        <div class="mt-1">
                            <input type="password" 
                                   name="smtp_password" 
                                   id="smtp_password"
                                   value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}" 
                                   class="form-input @error('smtp_password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="••••••••">
                        </div>
                        @error('smtp_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="smtp_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Remetente
                            </label>
                            <div class="mt-1">
                                <input type="email" 
                                       name="smtp_from_address" 
                                       id="smtp_from_address"
                                       value="{{ old('smtp_from_address', $settings['smtp_from_address'] ?? '') }}" 
                                       class="form-input @error('smtp_from_address') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       placeholder="noreply@exemplo.com">
                            </div>
                            @error('smtp_from_address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="smtp_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome Remetente
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="smtp_from_name" 
                                       id="smtp_from_name"
                                       value="{{ old('smtp_from_name', $settings['smtp_from_name'] ?? '') }}" 
                                       class="form-input @error('smtp_from_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       placeholder="Nova Rosa MT">
                            </div>
                            @error('smtp_from_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Salvar Parâmetros
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@extends('layouts.admin')

@php
    $title = 'Novo Cliente';
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
                            <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Clientes
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Novo Cliente</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl">
                Novo Cliente
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Cadastre um novo cliente no sistema
            </p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.customers.store') }}" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações Básicas
                </h3>
                
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

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Person Type -->
                    <div>
                        <label for="person_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo de Pessoa *
                        </label>
                        <div class="mt-1">
                            <select name="person_type" 
                                    id="person_type" 
                                    class="form-input @error('person_type') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                
                                <option value="PF" {{ old('person_type', 'PF') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{ old('person_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                        </div>
                        @error('person_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span id="name_label">Nome Completo</span> *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   required 
                                   value="{{ old('name') }}" 
                                   class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   autofocus>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date (PF only) -->
                    <div id="birth_date_group">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Data de Nascimento
                        </label>
                        <div class="mt-1">
                            <input type="date" 
                                   name="birth_date" 
                                   id="birth_date"
                                   value="{{ old('birth_date') }}" 
                                   class="form-input @error('birth_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CPF (PF) -->
                    <div id="cpf_group">
                        <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            CPF *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="cpf" 
                                   id="cpf"
                                   value="{{ old('cpf') }}" 
                                   maxlength="14" 
                                   required 
                                   class="form-input @error('cpf') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="000.000.000-00">
                        </div>
                        <p id="cpf_error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden">CPF inválido.</p>
                        @error('cpf')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNPJ (PJ) -->
                    <div id="cnpj_group" class="hidden">
                        <label for="cnpj" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            CNPJ *
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="cnpj" 
                                   id="cnpj"
                                   value="{{ old('cnpj') }}" 
                                   maxlength="18" 
                                   class="form-input @error('cnpj') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="00.000.000/0000-00">
                        </div>
                        <p id="cnpj_error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden">CNPJ inválido.</p>
                        @error('cnpj')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações de Contato
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <div class="mt-1">
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}" 
                                   class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="cliente@email.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Telefone
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   value="{{ old('phone') }}" 
                                   class="form-input @error('phone') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="(99) 99999-9999">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Será usado para contato via WhatsApp
                        </p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Endereço
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- CEP -->
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            CEP
                        </label>
                        <div class="mt-1 relative">
                            <input type="text" 
                                   name="zip_code" 
                                   id="zip_code"
                                   value="{{ old('zip_code') }}" 
                                   maxlength="9" 
                                   class="form-input @error('zip_code') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="00000-000"
                
                            <div id="cep_loading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('zip_code')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Street -->
                    <div class="sm:col-span-2">
                        <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Logradouro
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="street" 
                                   id="street"
                                   value="{{ old('street') }}" 
                                   class="form-input @error('street') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Rua, Avenida, etc.">
                        </div>
                        @error('street')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number -->
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Número
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="number" 
                                   id="number"
                                   value="{{ old('number') }}" 
                                   class="form-input @error('number') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="123">
                        </div>
                        @error('number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complement -->
                    <div>
                        <label for="complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Complemento
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="complement" 
                                   id="complement"
                                   value="{{ old('complement') }}" 
                                   class="form-input @error('complement') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Apto, Sala, etc.">
                        </div>
                        @error('complement')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Neighborhood -->
                    <div>
                        <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Bairro
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="neighborhood" 
                                   id="neighborhood"
                                   value="{{ old('neighborhood') }}" 
                                   class="form-input @error('neighborhood') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Centro">
                        </div>
                        @error('neighborhood')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cidade
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="city" 
                                   id="city"
                                   value="{{ old('city') }}" 
                                   class="form-input @error('city') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="São Paulo">
                        </div>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Estado
                        </label>
                        <div class="mt-1">
                            <select name="state" 
                                    id="state" 
                                    class="form-input @error('state') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Selecione...</option>
                                <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                            </select>
                        </div>
                        @error('state')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Informações Adicionais
                </h3>
                
                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <div class="mt-1">
                            <select name="status" 
                                    id="status" 
                                    class="form-input @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Observações
                        </label>
                        <div class="mt-1">
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="4" 
                                      class="form-input @error('notes') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                      placeholder="Informações adicionais sobre o cliente...">{{ old('notes') }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Máximo de 500 caracteres
                        </p>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.customers.index') }}" 
               class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </a>
            <button type="submit" 
                    class="btn-primary"
                    id="submit-btn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span id="submit-text">Salvar Cliente</span>
            </button>
        </div>
    </form>
</div>

<!-- JavaScript temporariamente removido para teste -->
@endsection
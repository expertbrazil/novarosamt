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

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Person Type -->
                    <div>
                        <label for="person_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo de Pessoa *
                        </label>
                        <div class="mt-1">
                            <select name="person_type" 
                                    id="person_type" 
                                    required
                                    class="form-input @error('person_type') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="PF" {{ old('person_type', 'PF') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{ old('person_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                        </div>
                        @error('person_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-2">
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

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Bairro
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="district" 
                                   id="district"
                                   value="{{ old('district') }}" 
                                   class="form-input @error('district') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Centro">
                        </div>
                        @error('district')
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
                            Estado (UF)
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="state" 
                                   id="state"
                                   value="{{ old('state') }}" 
                                   maxlength="2"
                                   class="form-input @error('state') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="MT">
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
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cliente Ativo
                            </span>
                        </label>
                        @error('is_active')
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const personTypeSelect = document.getElementById('person_type');
    const cpfGroup = document.getElementById('cpf_group');
    const cnpjGroup = document.getElementById('cnpj_group');
    const birthGroup = document.getElementById('birth_date_group');
    const nameLabel = document.getElementById('name_label');
    
    // Declarar variáveis de input primeiro
    const cpfInput = document.getElementById('cpf');
    const cnpjInput = document.getElementById('cnpj');

    function togglePessoa() {
        const tipo = personTypeSelect.value;
        
        if (tipo === 'PJ') {
            cnpjGroup.classList.remove('hidden');
            cpfGroup.classList.add('hidden');
            if (birthGroup) birthGroup.classList.add('hidden');
            if (cpfInput) cpfInput.required = false;
            if (cnpjInput) cnpjInput.required = true;
            if (nameLabel) nameLabel.textContent = 'Razão Social *';
        } else {
            cpfGroup.classList.remove('hidden');
            cnpjGroup.classList.add('hidden');
            if (birthGroup) birthGroup.classList.remove('hidden');
            if (cpfInput) cpfInput.required = true;
            if (cnpjInput) cnpjInput.required = false;
            if (nameLabel) nameLabel.textContent = 'Nome Completo';
        }
    }

    if (personTypeSelect) {
        personTypeSelect.addEventListener('change', togglePessoa);
        togglePessoa(); // Inicializar
    }

    // ===== Máscaras e validações CPF/CNPJ =====
    function onlyDigits(v){return (v||'').replace(/\D/g,'');}
    function maskCPF(v){v=onlyDigits(v).slice(0,11);return v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');}
    function maskCNPJ(v){v=onlyDigits(v).slice(0,14);return v.replace(/(\d{2})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1/$2').replace(/(\d{4})(\d{1,2})$/,'$1-$2');}
    function isValidCPF(cpf){cpf=onlyDigits(cpf);if(!cpf || cpf.length!==11||/(\d)\1{10}/.test(cpf))return false;for(let t=9;t<11;t++){let d=0;for(let c=0;c<t;c++){d+=cpf[c]*((t+1)-c);}d=((10*d)%11)%10;if(cpf[t]!=d)return false;}return true;}
    function isValidCNPJ(cnpj){cnpj=onlyDigits(cnpj);if(!cnpj || cnpj.length!==14||/(\d)\1{13}/.test(cnpj))return false;let t=[5,4,3,2,9,8,7,6,5,4,3,2],s=0;for(let i=0;i<12;i++)s+=cnpj[i]*t[i];let r=s%11,d1=(r<2)?0:11-r;t=[6,5,4,3,2,9,8,7,6,5,4,3,2];s=0;for(let i=0;i<13;i++)s+=cnpj[i]*t[i];r=s%11;let d2=(r<2)?0:11-r;return (cnpj[12]==d1 && cnpj[13]==d2);} 
    
    const cpfError=document.getElementById('cpf_error');
    if(cpfInput){
      const applyCpf=()=>{
        const masked=maskCPF(cpfInput.value);
        cpfInput.value=masked;
        const ok=isValidCPF(cpfInput.value);
        if(masked && !ok){
          cpfInput.classList.add('border-red-500');
          cpfError.classList.remove('hidden');
        } else {
          cpfInput.classList.remove('border-red-500');
          cpfError.classList.add('hidden');
        }
      };
      cpfInput.addEventListener('input',applyCpf);cpfInput.addEventListener('blur',applyCpf);
    }
    
    const cnpjError=document.getElementById('cnpj_error');
    if(cnpjInput){
      const applyCnpj=()=>{
        const masked=maskCNPJ(cnpjInput.value);
        cnpjInput.value=masked;
        const ok=isValidCNPJ(cnpjInput.value);
        if(masked && !ok){
          cnpjInput.classList.add('border-red-500');
          cnpjError.classList.remove('hidden');
        } else {
          cnpjInput.classList.remove('border-red-500');
          cnpjError.classList.add('hidden');
        }
      };
      cnpjInput.addEventListener('input',applyCnpj);cnpjInput.addEventListener('blur',applyCnpj);
    }
    
    // Máscara telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        // aplica máscara inicial se vier sem formatação
        (function(){
            let v = phoneInput.value || '';
            v = v.replace(/\D/g,'');
            if (v.length === 10) phoneInput.value = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            else if (v.length === 11) phoneInput.value = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        })();
        phoneInput.addEventListener('input', function(){
            let v = this.value.replace(/\D/g,'').slice(0,11);
            if (v.length > 10) {
                this.value = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (v.length > 6) {
                this.value = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (v.length > 2) {
                this.value = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            } else {
                this.value = v.replace(/(\d{0,2})/, '($1');
            }
        });
    }
    
    // Máscara de CEP e integração ViaCEP
    const cepInput = document.getElementById('zip_code');
    if (cepInput) {
        // aplica máscara inicial se vier sem formatação
        (function(){
            let v = cepInput.value || '';
            v = v.replace(/\D/g,'');
            if (v.length === 8) cepInput.value = v.replace(/(\d{5})(\d{3})/, '$1-$2');
        })();
        cepInput.addEventListener('input', function(){
            let v = this.value.replace(/\D/g,'').slice(0,8);
            if (v.length > 5) {
                this.value = v.replace(/(\d{5})(\d{3})/, '$1-$2');
            } else {
                this.value = v;
            }
            
            // Buscar CEP quando completo
            if (v.length === 8) {
                fetchCep(v);
            }
        });
        
        // Integração ViaCEP
        let cepSearchTimeout;
        function fetchCep(cep) {
            clearTimeout(cepSearchTimeout);
            cepSearchTimeout = setTimeout(async () => {
                const loading = document.getElementById('cep_loading');
                try {
                    if (loading) loading.classList.remove('hidden');
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    
                    if (!data.erro && data) {
                        document.getElementById('street').value = data.logradouro || '';
                        document.getElementById('district').value = data.bairro || '';
                        document.getElementById('city').value = data.localidade || '';
                        document.getElementById('state').value = data.uf || '';
                        // Focar no campo número após preencher
                        document.getElementById('number').focus();
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                } finally {
                    if (loading) loading.classList.add('hidden');
                }
            }, 300);
        }
    }
    
    // Máscara para estado (UF) - converter para maiúsculas e limitar a 2 caracteres
    const stateInput = document.getElementById('state');
    if (stateInput) {
        stateInput.addEventListener('input', function(){
            this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0,2);
        });
    }

    // Sanitiza CPF/CNPJ, telefone e CEP antes de enviar
    document.querySelector('form').addEventListener('submit',function(){
      if(cpfInput) cpfInput.value=onlyDigits(cpfInput.value);
      if(cnpjInput) cnpjInput.value=onlyDigits(cnpjInput.value);
      if (phoneInput) phoneInput.value = phoneInput.value.replace(/\D/g,'');
      if (cepInput) cepInput.value = cepInput.value.replace(/\D/g,'');
    });
});
</script>
@endsection
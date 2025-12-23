@extends('layouts.admin')

@php
    $title = 'Evolution API - Integração WhatsApp';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Evolution API - Integração WhatsApp</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Configure a integração com a Evolution API para envio de mensagens pelo WhatsApp
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

    <!-- Configuração da Evolution API -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    Configuração da Evolution API
                </h3>
            </div>

            <form method="POST" action="{{ route('admin.evolution-api.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Switcher de Ativação -->
                <div class="flex items-center justify-between">
                    <div>
                        <label for="evolution_api_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Usar Evolution API
                        </label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Ative ou desative o uso da Evolution API para envio de mensagens
                        </p>
                    </div>
                    <div class="ml-4">
                        @php
                            $isEnabled = old('evolution_api_enabled', $settings['evolution_api_enabled'] ?? '0');
                            $isEnabled = ($isEnabled == '1' || $isEnabled == 1 || $isEnabled === true);
                        @endphp
                        <label for="evolution_api_enabled" class="relative inline-flex items-center cursor-pointer">
                            {{-- Campo hidden para garantir que sempre envie um valor quando checkbox não estiver marcado --}}
                            <input type="hidden" name="evolution_api_enabled" value="0" id="evolution_api_enabled_hidden" {{ $isEnabled ? 'disabled' : '' }}>
                            <input type="checkbox" 
                                   name="evolution_api_enabled" 
                                   id="evolution_api_enabled"
                                   value="1"
                                   {{ $isEnabled ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out {{ $isEnabled ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }} peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800">
                                <div class="absolute top-[2px] bg-white dark:bg-gray-300 border border-gray-300 dark:border-gray-600 rounded-full h-5 w-5 transition-all duration-200 ease-in-out {{ $isEnabled ? 'left-[22px]' : 'left-[2px]' }}"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="evolution_api_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Link da Evolution API <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="url" 
                               name="evolution_api_url" 
                               id="evolution_api_url"
                               value="{{ old('evolution_api_url', $settings['evolution_api_url'] ?? '') }}" 
                               class="form-input @error('evolution_api_url') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               placeholder="https://expertbrazil-evolution-api.wslmce.easypanel.host">
                    </div>
                    @error('evolution_api_url')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        URL completa da sua Evolution API
                    </p>
                </div>

                <div>
                    <label for="evolution_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Token da Instância <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="evolution_api_key" 
                               id="evolution_api_key"
                               value="{{ old('evolution_api_key', $settings['evolution_api_key'] ?? '') }}" 
                               class="form-input @error('evolution_api_key') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               placeholder="E9BB6A64AF01-435F-89B1-238F70DD451A">
                    </div>
                    @error('evolution_api_key')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Token específico da instância
                    </p>
                </div>

                <div>
                    <label for="evolution_instance_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nome da Instância <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="evolution_instance_name" 
                               id="evolution_instance_name"
                               value="{{ old('evolution_instance_name', $settings['evolution_instance_name'] ?? '') }}" 
                               class="form-input @error('evolution_instance_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               placeholder="ExpertBrazil">
                    </div>
                    @error('evolution_instance_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Nome da instância já criada na Evolution API
                    </p>
                </div>

                <div>
                    <label for="evolution_whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Número WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="evolution_whatsapp_number" 
                               id="evolution_whatsapp_number"
                               value="{{ old('evolution_whatsapp_number', $settings['evolution_whatsapp_number'] ?? '') }}" 
                               class="form-input @error('evolution_whatsapp_number') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               placeholder="551194783455">
                    </div>
                    @error('evolution_whatsapp_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Número com DDI (ex: 551194783455)
                    </p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status da Instância -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    Status da Instância
                </h3>
            </div>

            <div class="space-y-4">
                @if($instanceStatus && isset($instanceStatus['instance']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome da Instância
                            </label>
                            <input type="text" 
                                   value="{{ $instanceStatus['instance']['instance']['instanceName'] ?? ($settings['evolution_instance_name'] ?? 'N/A') }}" 
                                   readonly
                                   class="form-input bg-gray-50 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Número WhatsApp
                            </label>
                            <input type="text" 
                                   value="{{ $instanceStatus['instance']['instance']['phone'] ?? ($settings['evolution_whatsapp_number'] ?? 'N/A') }}" 
                                   readonly
                                   class="form-input bg-gray-50 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        @if($instanceStatus['connected'] ?? false)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Conectado
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Desconectado
                            </span>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome da Instância
                            </label>
                            <input type="text" 
                                   value="{{ $settings['evolution_instance_name'] ?? 'N/A' }}" 
                                   readonly
                                   class="form-input bg-gray-50 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Número WhatsApp
                            </label>
                            <input type="text" 
                                   value="{{ $settings['evolution_whatsapp_number'] ?? 'N/A' }}" 
                                   readonly
                                   class="form-input bg-gray-50 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            Não verificado
                        </span>
                    </div>
                @endif

                <div class="flex gap-3 pt-4">
                    <button type="button" 
                            id="testEvolutionConnectionBtn"
                            class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Verificar Status
                    </button>
                    @if($instanceStatus && ($instanceStatus['connected'] ?? false))
                    <button type="button" 
                            id="disconnectBtn"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-600">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                        Desconectar
                    </button>
                    @endif
                </div>
                <div id="evolutionTestResult" class="hidden"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gerenciar campo hidden do switcher e atualizar visual
    const checkbox = document.getElementById('evolution_api_enabled');
    const hiddenField = document.getElementById('evolution_api_enabled_hidden');
    const toggleDiv = checkbox?.nextElementSibling;
    
    if (checkbox && hiddenField && toggleDiv) {
        // Função para atualizar visual
        function updateToggleVisual() {
            const innerDiv = toggleDiv.querySelector('div');
            if (checkbox.checked) {
                // Ativo - roxo, bolinha à direita
                toggleDiv.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                toggleDiv.classList.add('bg-indigo-600');
                if (innerDiv) {
                    innerDiv.classList.remove('left-[2px]');
                    innerDiv.classList.add('left-[22px]');
                }
                hiddenField.disabled = true;
            } else {
                // Inativo - cinza, bolinha à esquerda
                toggleDiv.classList.remove('bg-indigo-600');
                toggleDiv.classList.add('bg-gray-200', 'dark:bg-gray-700');
                if (innerDiv) {
                    innerDiv.classList.remove('left-[22px]');
                    innerDiv.classList.add('left-[2px]');
                }
                hiddenField.disabled = false;
            }
        }
        
        // Quando checkbox muda
        checkbox.addEventListener('change', updateToggleVisual);
        
        // Inicializar estado visual
        updateToggleVisual();
    }
    
    // Testar conexão Evolution API
    const testEvolutionBtn = document.getElementById('testEvolutionConnectionBtn');
    const evolutionTestResult = document.getElementById('evolutionTestResult');
    
    if (testEvolutionBtn) {
        testEvolutionBtn.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            // Desabilitar botão e mostrar loading
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Verificando...';
            
            // Esconder resultado anterior
            evolutionTestResult.classList.add('hidden');
            
            // Fazer requisição
            fetch('{{ route("admin.evolution-api.test-connection") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Restaurar botão
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                // Mostrar resultado
                evolutionTestResult.classList.remove('hidden');
                
                if (data.success) {
                    evolutionTestResult.className = 'mt-3 rounded-md bg-green-50 dark:bg-green-900/20 p-4';
                    evolutionTestResult.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    ${data.message}
                                </p>
                            </div>
                        </div>
                    `;
                    // Recarregar página após 2 segundos para atualizar status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    evolutionTestResult.className = 'mt-3 rounded-md bg-red-50 dark:bg-red-900/20 p-4';
                    let errorContent = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                    ${data.message || 'Erro ao testar conexão'}
                                </p>
                                ${data.available_instances && data.available_instances.length > 0 ? `
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                        <p class="font-semibold mb-1">Instâncias disponíveis na Evolution API:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            ${data.available_instances.map(inst => `<li>${inst}</li>`).join('')}
                                        </ul>
                                        ${data.suggestion ? `<p class="mt-2 font-medium">${data.suggestion}</p>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    evolutionTestResult.innerHTML = errorContent;
                }
            })
            .catch(error => {
                // Restaurar botão
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                // Mostrar erro
                evolutionTestResult.classList.remove('hidden');
                evolutionTestResult.className = 'mt-3 rounded-md bg-red-50 dark:bg-red-900/20 p-4';
                evolutionTestResult.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                Erro ao testar conexão: ${error.message}
                            </p>
                        </div>
                    </div>
                `;
            });
        });
    }

    // Botão Desconectar (placeholder - implementar funcionalidade se necessário)
    const disconnectBtn = document.getElementById('disconnectBtn');
    if (disconnectBtn) {
        disconnectBtn.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja desconectar a instância?')) {
                // Implementar lógica de desconexão se necessário
                alert('Funcionalidade de desconexão será implementada em breve.');
            }
        });
    }
});
</script>
@endsection

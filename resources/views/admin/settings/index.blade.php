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

        <!-- Logo para Pedidos e Relatórios -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Logo para Pedidos e Relatórios
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Envie uma logomarca específica para aparecer nos PDFs e impressões de pedidos
                    </p>
                </div>

                <div class="space-y-4">
                    @if(isset($settings['orders_logo']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Logo Atual para Pedidos
                            </label>
                            <img src="{{ Storage::url($settings['orders_logo']) }}" 
                                 alt="Logo Pedidos" 
                                 class="h-32 w-auto object-contain border border-gray-300 dark:border-gray-600 rounded-lg p-2">
                        </div>
                    @endif

                    <div>
                        <label for="orders_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ isset($settings['orders_logo']) ? 'Alterar Logo de Pedidos' : 'Enviar Logo de Pedidos' }}
                        </label>
                        <div class="mt-1">
                            <input type="file" 
                                   name="orders_logo" 
                                   id="orders_logo"
                                   accept="image/*"
                                   class="form-input @error('orders_logo') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        </div>
                        @error('orders_logo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Formatos aceitos: JPG, PNG, SVG. Tamanho máximo: 2MB. Esta logo será usada nos PDFs e impressões de pedidos.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrega -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Informações de Entrega
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Configure as informações sobre entrega para os clientes
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="company_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Endereço da Sede da Empresa
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="company_address" 
                                   id="company_address"
                                   value="{{ old('company_address', $settings['company_address'] ?? '') }}"
                                   class="form-input @error('company_address') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="Ex: Rua Exemplo, 123 - Centro - Nova Rosa, MT - CEP 12345-678">
                        </div>
                        @error('company_address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Endereço completo da sede da empresa que será exibido no site
                        </p>
                    </div>

                    <div>
                        <label for="delivery_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Informações sobre Entrega
                        </label>
                        <div class="mt-1">
                            <textarea name="delivery_info" 
                                      id="delivery_info"
                                      rows="4"
                                      class="form-input @error('delivery_info') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                      placeholder="Ex: Entregamos em até 3 dias úteis. Frete grátis para compras acima de R$ 100,00...">{{ old('delivery_info', $settings['delivery_info'] ?? '') }}</textarea>
                        </div>
                        @error('delivery_info')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Informações que serão exibidas para o cliente sobre a entrega
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="delivery_cities" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cidades de Entrega
                            </label>
                            <button type="button" 
                                    id="addDeliveryCityBtn"
                                    class="btn-secondary text-sm py-1 px-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Adicionar Cidade
                            </button>
                        </div>
                        
                        <div id="deliveryCitiesRepeater" class="space-y-3">
                            <!-- Template para nova linha (hidden) -->
                            <template id="deliveryCityRowTemplate">
                                <div class="delivery-city-row bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Estado
                                            </label>
                                            <select name="delivery_cities[IDX][estado]" 
                                                    class="delivery-estado-select form-input" 
                                                    required>
                                                <option value="">Selecione o estado</option>
                                                @foreach($estados as $estado)
                                                    <option value="{{ $estado->estado }}">{{ $estado->estado_nome }} ({{ $estado->estado }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Cidade
                                            </label>
                                            <select name="delivery_cities[IDX][municipio_id]" 
                                                    class="delivery-municipio-select form-input" 
                                                    required>
                                                <option value="">Primeiro selecione o estado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="button" 
                                                class="remove-delivery-city-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Linhas existentes -->
                            @if(!empty($deliveryCities) && is_array($deliveryCities) && count($deliveryCities) > 0)
                                @foreach($deliveryCities as $index => $cityData)
                                    @php
                                        $municipio = \App\Models\EstadoMunicipio::find($cityData['municipio_id'] ?? $cityData);
                                        $estadoSelected = $municipio ? $municipio->estado : '';
                                        $municipios = $municipio ? \App\Models\EstadoMunicipio::getByEstado($municipio->estado) : collect();
                                    @endphp
                                    <div class="delivery-city-row bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Estado
                                                </label>
                                                <select name="delivery_cities[{{ $index }}][estado]" 
                                                        class="delivery-estado-select form-input" 
                                                        required>
                                                    <option value="">Selecione o estado</option>
                                                    @foreach($estados as $estado)
                                                        <option value="{{ $estado->estado }}" {{ $estadoSelected == $estado->estado ? 'selected' : '' }}>
                                                            {{ $estado->estado_nome }} ({{ $estado->estado }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Cidade
                                                </label>
                                                <select name="delivery_cities[{{ $index }}][municipio_id]" 
                                                        class="delivery-municipio-select form-input" 
                                                        required>
                                                    <option value="">Selecione a cidade</option>
                                                    @foreach($municipios as $municipio)
                                                        <option value="{{ $municipio->id }}" {{ ($cityData['municipio_id'] ?? $cityData) == $municipio->id ? 'selected' : '' }}>
                                                            {{ $municipio->municipio }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <button type="button" 
                                                    class="remove-delivery-city-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Linha vazia inicial -->
                                <div class="delivery-city-row bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Estado
                                            </label>
                                            <select name="delivery_cities[0][estado]" 
                                                    class="delivery-estado-select form-input" 
                                                    required>
                                                <option value="">Selecione o estado</option>
                                                @foreach($estados as $estado)
                                                    <option value="{{ $estado->estado }}">{{ $estado->estado_nome }} ({{ $estado->estado }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Cidade
                                            </label>
                                            <select name="delivery_cities[0][municipio_id]" 
                                                    class="delivery-municipio-select form-input" 
                                                    required>
                                                <option value="">Primeiro selecione o estado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="button" 
                                                class="remove-delivery-city-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium opacity-50 cursor-not-allowed"
                                                disabled>
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @error('delivery_cities')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Adicione as cidades onde a entrega é realizada. Selecione o estado e depois a cidade.
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

        <!-- Dados para Pagamento -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Dados para Pagamento
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informe quem irá receber e a chave Pix utilizada nos pedidos e comunicações.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="payment_recipient_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome do Recebedor
                        </label>
                        <div class="mt-1">
                            <input type="text"
                                   name="payment_recipient_name"
                                   id="payment_recipient_name"
                                   value="{{ old('payment_recipient_name', $settings['payment_recipient_name'] ?? '') }}"
                                   class="form-input @error('payment_recipient_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Ex: Maria da Silva">
                        </div>
                        @error('payment_recipient_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Esse nome pode ser exibido nos pedidos e emails para orientar quem realizará o pagamento.
                        </p>
                    </div>

                    <div>
                        <label for="payment_pix_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Chave PIX
                        </label>
                        <div class="mt-1">
                            <input type="text"
                                   name="payment_pix_key"
                                   id="payment_pix_key"
                                   value="{{ old('payment_pix_key', $settings['payment_pix_key'] ?? '') }}"
                                   class="form-input @error('payment_pix_key') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="CPF, CNPJ, Email ou Chave Aleatória">
                        </div>
                        @error('payment_pix_key')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Esta chave poderá ser usada para instruções de pagamento em PDF, email e WhatsApp.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Analytics -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Google Analytics
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Configure o Google Analytics para monitorar os acessos dos clientes
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ID de Medição (Measurement ID)
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="google_analytics_id" 
                                   id="google_analytics_id"
                                   value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" 
                                   class="form-input @error('google_analytics_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   placeholder="G-XXXXXXXXXX">
                        </div>
                        @error('google_analytics_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Informe o ID de medição do Google Analytics 4 (formato: G-XXXXXXXXXX). 
                            Você pode encontrar este ID em: Admin → Propriedades → Informações da propriedade → ID de medição.
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeater = document.getElementById('deliveryCitiesRepeater');
    const template = document.getElementById('deliveryCityRowTemplate');
    const addBtn = document.getElementById('addDeliveryCityBtn');
    
    if (!repeater || !template || !addBtn) return;

    // Adicionar nova linha
    addBtn.addEventListener('click', function() {
        const index = repeater.querySelectorAll('.delivery-city-row').length;
        const clone = document.importNode(template.content, true);
        const row = clone.querySelector('.delivery-city-row');
        
        // Atualizar índices nos campos
        row.querySelectorAll('select[name]').forEach(select => {
            select.name = select.name.replace('IDX', index);
        });
        
        repeater.appendChild(clone);
        bindRowEvents(row);
        
        // Habilitar botões de remover se houver mais de uma linha
        if (repeater.querySelectorAll('.delivery-city-row').length > 1) {
            repeater.querySelectorAll('.remove-delivery-city-btn').forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }
    });

    // Remover linha
    repeater.addEventListener('click', function(e) {
        if (e.target.closest('.remove-delivery-city-btn')) {
            const btn = e.target.closest('.remove-delivery-city-btn');
            if (btn.disabled) return;
            
            const row = e.target.closest('.delivery-city-row');
            const totalRows = repeater.querySelectorAll('.delivery-city-row').length;
            
            if (row && totalRows > 1) {
                row.remove();
                // Se restar apenas uma linha, desabilitar o botão de remover
                if (repeater.querySelectorAll('.delivery-city-row').length === 1) {
                    const remainingBtn = repeater.querySelector('.remove-delivery-city-btn');
                    if (remainingBtn) {
                        remainingBtn.disabled = true;
                        remainingBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            } else if (row) {
                // Se for a última linha, apenas limpar os campos
                row.querySelectorAll('select').forEach(select => {
                    select.value = '';
                    if (select.classList.contains('delivery-municipio-select')) {
                        select.innerHTML = '<option value="">Primeiro selecione o estado</option>';
                    }
                });
            }
        }
    });

    // Carregar municípios quando estado mudar
    function bindRowEvents(row) {
        const estadoSelect = row.querySelector('.delivery-estado-select');
        const municipioSelect = row.querySelector('.delivery-municipio-select');
        
        if (estadoSelect) {
            estadoSelect.addEventListener('change', function() {
                const estado = this.value;
                municipioSelect.innerHTML = '<option value="">Carregando...</option>';
                municipioSelect.disabled = true;
                
                if (estado) {
                    fetch(`/admin/settings/municipios/${estado}`)
                        .then(response => response.json())
                        .then(data => {
                            municipioSelect.innerHTML = '<option value="">Selecione a cidade</option>';
                            data.forEach(municipio => {
                                const option = document.createElement('option');
                                option.value = municipio.id;
                                option.textContent = municipio.municipio;
                                municipioSelect.appendChild(option);
                            });
                            municipioSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Erro ao carregar municípios:', error);
                            municipioSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                            municipioSelect.disabled = false;
                        });
                } else {
                    municipioSelect.innerHTML = '<option value="">Primeiro selecione o estado</option>';
                    municipioSelect.disabled = false;
                }
            });
        }
    }

    // Bind eventos nas linhas existentes
    repeater.querySelectorAll('.delivery-city-row').forEach(row => {
        bindRowEvents(row);
    });
});
</script>
@endsection


<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal de produtos de limpeza profissionais e domésticos - Nova Rosa MT. Encontre os melhores produtos para sua limpeza.">
    <meta name="keywords" content="produtos de limpeza, limpeza profissional, detergentes, desinfetantes, Nova Rosa MT">
    <title>{{ $title ?? 'Portal de Produtos de Limpeza - Nova Rosa MT' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center py-2">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        @if(isset($settings['logo']) && !empty($settings['logo']))
                            <img src="{{ Storage::url($settings['logo']) }}" 
                                 alt="Logo Nova Rosa MT" 
                                 class="h-16 w-auto object-contain">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Nova Rosa MT</span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                        Início
                    </a>
                    <a href="{{ route('order.create') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Fazer Pedido
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Abrir menu principal</span>
                        <!-- Menu icon -->
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Close icon -->
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden" id="mobile-menu" style="display: none;">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('home') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                        Início
                    </a>
                    <a href="{{ route('order.create') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200">
                        Fazer Pedido
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-gray-950 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        @if(isset($settings['logo']) && !empty($settings['logo']))
                            <img src="{{ Storage::url($settings['logo']) }}" 
                                 alt="Logo Nova Rosa MT" 
                                 class="h-14 w-auto object-contain">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold">Nova Rosa MT</span>
                        @endif
                    </div>
                    <p class="text-gray-300 text-sm">
                        Produtos de limpeza profissionais e domésticos de alta qualidade para todas as suas necessidades.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Início</a>
                        </li>
                        <li>
                            <a href="{{ route('order.create') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Fazer Pedido</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Contato</h3>
                    <div class="space-y-2 text-gray-300 text-sm">
                        <p>Nova Rosa, MT</p>
                        <p>Brasil</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-800">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Nova Rosa MT. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = mobileMenuButton.querySelector('svg:first-child');
            const closeIcon = mobileMenuButton.querySelector('svg:last-child');
            
            mobileMenuButton.addEventListener('click', function() {
                const isOpen = mobileMenu.style.display === 'block';
                
                if (isOpen) {
                    mobileMenu.style.display = 'none';
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                } else {
                    mobileMenu.style.display = 'block';
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'true');
                }
            });
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.style.display = 'none';
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>


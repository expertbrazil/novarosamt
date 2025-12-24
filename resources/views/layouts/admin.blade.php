<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Painel administrativo - Nova Rosa MT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? ($title . ' · ') : '' }}Admin - Nova Rosa MT</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @if(file_exists(public_path('favicon-96x96.png')))
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    @endif
    @if(file_exists(public_path('apple-touch-icon.png')))
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @endif
    @if(file_exists(public_path('web-app-manifest-192x192.png')))
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('web-app-manifest-192x192.png') }}">
    @endif
    @if(file_exists(public_path('web-app-manifest-512x512.png')))
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('web-app-manifest-512x512.png') }}">
    @endif
    @if(file_exists(public_path('site.webmanifest')))
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @endif
    <meta name="theme-color" content="#6366f1">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Off-canvas menu for mobile -->
        <div id="mobile-sidebar" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" style="display: none;">
            <!-- Background backdrop -->
            <div class="fixed inset-0 bg-gray-900/80 transition-opacity duration-300 ease-linear" aria-hidden="true"></div>
            
            <div class="fixed inset-0 flex">
                <!-- Off-canvas menu -->
                <div class="relative mr-16 flex w-full max-w-xs flex-1">
                    <!-- Close button -->
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" id="close-sidebar" class="-m-2.5 p-2.5" aria-label="Fechar sidebar">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Sidebar component -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-900 px-6 pb-4 ring-1 ring-white/10">
                        <div class="flex h-16 shrink-0 items-center">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">Nova Rosa MT</span>
                            </a>
                        </div>
                        @include('admin.partials.sidebar')
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-900 px-6 pb-4 border-r border-gray-200 dark:border-gray-700">
                <div class="flex h-16 shrink-0 items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Nova Rosa MT</span>
                    </a>
                </div>
                @include('admin.partials.sidebar')
            </div>
        </div>

        <div class="lg:pl-72">
            <!-- Sticky search header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" id="open-sidebar" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden" aria-label="Abrir sidebar">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="relative flex flex-1 items-center">
                        <h1 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications button -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" aria-label="Ver notificações">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:lg:bg-gray-700" aria-hidden="true"></div>

                        <!-- Profile dropdown -->
                        <div class="relative">
                            <button type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Abrir menu do usuário</span>
                                <div class="h-8 w-8 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true">{{ auth()->user()->name ?? 'Admin' }}</span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="user-menu" class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                                <a href="{{ route('admin.profile.show') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem" tabindex="-1">
                                    Meu Perfil
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="block w-full px-3 py-1 text-left text-sm leading-6 text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem" tabindex="-1">Sair</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            
            function openSidebar() {
                mobileSidebar.style.display = 'block';
                setTimeout(() => {
                    mobileSidebar.classList.add('opacity-100');
                }, 10);
            }
            
            function closeSidebar() {
                mobileSidebar.classList.remove('opacity-100');
                setTimeout(() => {
                    mobileSidebar.style.display = 'none';
                }, 300);
            }
            
            openSidebarBtn?.addEventListener('click', openSidebar);
            closeSidebarBtn?.addEventListener('click', closeSidebar);
            
            // Close on backdrop click
            mobileSidebar?.addEventListener('click', function(e) {
                if (e.target === mobileSidebar || e.target.classList.contains('bg-gray-900/80')) {
                    closeSidebar();
                }
            });
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileSidebar.style.display === 'block') {
                    closeSidebar();
                }
            });
            
            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            userMenuButton?.addEventListener('click', function() {
                const isOpen = userMenu.style.display === 'block';
                userMenu.style.display = isOpen ? 'none' : 'block';
                userMenuButton.setAttribute('aria-expanded', !isOpen);
            });
            
            // Close user menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton?.contains(e.target) && !userMenu?.contains(e.target)) {
                    userMenu.style.display = 'none';
                    userMenuButton?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
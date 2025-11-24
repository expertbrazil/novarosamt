@php
    $navigation = [
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
            'current' => request()->routeIs('admin.dashboard'),
            'icon' => 'M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z',
        ],
        [
            'name' => 'Produtos',
            'href' => route('admin.products.index'),
            'current' => request()->routeIs('admin.products.*'),
            'icon' => 'M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z',
        ],
        [
            'name' => 'Categorias',
            'href' => route('admin.categories.index'),
            'current' => request()->routeIs('admin.categories.*'),
            'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z',
        ],
        [
            'name' => 'Pedidos',
            'href' => route('admin.orders.index'),
            'current' => request()->routeIs('admin.orders.*'),
            'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
        ],
        [
            'name' => 'Pedidos de Compra',
            'href' => route('admin.purchase-orders.index'),
            'current' => request()->routeIs('admin.purchase-orders.*'),
            'icon' => 'M3 5.25h18M5.25 21h13.5c.621 0 1.125-.504 1.125-1.125V6M5.25 21A2.25 2.25 0 013 18.75V6m6 8.25h6',
        ],
        [
            'name' => 'Clientes',
            'href' => route('admin.customers.index'),
            'current' => request()->routeIs('admin.customers.*'),
            'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        ],
        [
            'name' => 'Estoque',
            'href' => route('admin.stock.index'),
            'current' => request()->routeIs('admin.stock.*'),
            'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
        ],
        [
            'name' => 'Parâmetros',
            'href' => route('admin.settings.index'),
            'current' => request()->routeIs('admin.settings.*'),
            'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        ],
    ];
@endphp

<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                @foreach ($navigation as $item)
                    <li>
                        <a href="{{ $item['href'] }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors duration-200 ease-in-out
                                  {{ $item['current'] 
                                     ? 'bg-indigo-700 text-white' 
                                     : 'text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                           aria-current="{{ $item['current'] ? 'page' : 'false' }}">
                            <svg class="h-6 w-6 shrink-0 {{ $item['current'] ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400' }}" 
                                 fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        
        <!-- Quick Actions Section -->
        <li class="mt-auto">
            <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-gray-500">Ações Rápidas</div>
            <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li>
                    <a href="{{ route('admin.products.create') }}" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Novo Produto
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.create') }}" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Novo Pedido
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.purchase-orders.create') }}" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6m12 9H6A2.25 2.25 0 013.75 18.75V6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25z" />
                        </svg>
                        Pedido de Compra
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Footer -->
<div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
    <div class="flex items-center gap-x-4 px-2 py-3 text-sm font-semibold leading-6 text-gray-900 dark:text-white">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800">
            <svg class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
            </svg>
        </div>
        <span class="sr-only">Sua empresa</span>
        <span aria-hidden="true">Nova Rosa MT</span>
    </div>
    <p class="px-2 text-xs leading-5 text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} Nova Rosa MT. Todos os direitos reservados.
    </p>
</div>




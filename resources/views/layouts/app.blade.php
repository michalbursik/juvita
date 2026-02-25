<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'receipt': 'rgba(25, 135, 84, 0.1)',
                        'issue': 'rgba(220, 53, 69, 0.1)',
                        'transmission': 'rgba(255, 193, 7, 0.1)',
                        'check': 'rgba(13, 110, 253, 0.1)',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .receipt-color { background-color: rgba(25, 135, 84, 0.1); }
        .issue-color { background-color: rgba(220, 53, 69, 0.1); }
        .transmission-color { background-color: rgba(255, 193, 7, 0.1); }
        .check-color { background-color: rgba(13, 110, 253, 0.1); }

        .pad {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80px;
            font-size: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            user-select: none;
            background-color: #f8f9fa;
        }
        .pad:active {
            background-color: #e2e3e5;
        }
        .pad.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>

    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-50 text-gray-900">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="font-bold text-xl text-gray-800">
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                @if(auth()->user()->role->isAdmin())
                                    <x-nav-link href="{{ route('overviews.index') }}" :active="request()->routeIs('overviews.*')">
                                        Přehledy
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('warehouses.index') }}" :active="request()->routeIs('warehouses.index') || request()->routeIs('warehouses.create') || request()->routeIs('warehouses.edit')">
                                        Sklady
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('movements.index') }}" :active="request()->routeIs('movements.*')">
                                        Pohyby
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('discounts.index') }}" :active="request()->routeIs('discounts.*')">
                                        Slevy
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('checks.index') }}" :active="request()->routeIs('checks.*')">
                                        Kontroly
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                                        Produkty
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')">
                                        Uživatelé
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('warehouses.trash') }}" :active="request()->routeIs('warehouses.trash')">
                                        Kompost/Odpad
                                    </x-nav-link>
                                @else
                                    <x-nav-link href="{{ route('warehouses.show', auth()->user()->warehouse_id) }}" :active="request()->routeIs('warehouses.show') && request()->route('warehouse')->id == auth()->user()->warehouse_id">
                                        Můj sklad
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('movements.index') }}" :active="request()->routeIs('movements.*')">
                                        Pohyby
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('discounts.index') }}" :active="request()->routeIs('discounts.*')">
                                        Slevy
                                    </x-nav-link>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>
                        @else
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>

                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Odhlásit se
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                    <div class="flex items-center sm:hidden">
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-screen max-w-[250px] rounded shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" x-cloak>
                                    <div class="px-4 py-2 border-b border-gray-100 font-bold text-gray-700">
                                        {{ auth()->user()->name }}
                                    </div>
                                    @if(auth()->user()->role->isAdmin())
                                        <a href="{{ route('overviews.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('overviews.*') ? 'bg-gray-50 font-bold' : '' }}">Přehledy</a>
                                        <a href="{{ route('warehouses.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('warehouses.*') ? 'bg-gray-50 font-bold' : '' }}">Sklady</a>
                                        <a href="{{ route('movements.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('movements.*') ? 'bg-gray-50 font-bold' : '' }}">Pohyby</a>
                                        <a href="{{ route('discounts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('discounts.*') ? 'bg-gray-50 font-bold' : '' }}">Slevy</a>
                                        <a href="{{ route('checks.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('checks.*') ? 'bg-gray-50 font-bold' : '' }}">Kontroly</a>
                                        <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('products.*') ? 'bg-gray-50 font-bold' : '' }}">Produkty</a>
                                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-gray-50 font-bold' : '' }}">Uživatelé</a>
                                        <a href="{{ route('warehouses.trash') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('warehouses.trash') ? 'bg-gray-50 font-bold' : '' }}">Kompost/Odpad</a>
                                    @else
                                        <a href="{{ route('warehouses.show', auth()->user()->warehouse_id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Můj sklad</a>
                                        <a href="{{ route('movements.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pohyby</a>
                                        <a href="{{ route('discounts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Slevy</a>
                                    @endif
                                    <div class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 font-bold hover:bg-rose-50">
                                                Odhlásit se
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(isset($slot))
                    {{ $slot }}
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>

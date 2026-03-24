<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'La Vie De Vin')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #111111;
            color: #e5e5e5;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .layout-border {
            border-color: rgba(255, 255, 255, 0.06);
        }

        .nav-link {
            color: #d1d5db;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #b91c1c;
        }

        .btn-main {
            background: #b91c1c;
            color: #fff;
            padding: 0.65rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-main:hover {
            background: #991b1b;
        }
    </style>
</head>

<body class="antialiased">

    @include('partials.age-verification-modal')

    <div id="main-content" style="opacity: 0; transition: opacity 1s ease;">

        <header class="fixed top-0 left-0 right-0 bg-[#111111] border-b layout-border z-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-20">

                    <a href="{{ route('home') }}" class="text-xl tracking-widest font-light text-white">
                        LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
                    </a>

                    <nav class="hidden md:flex items-center space-x-10 text-sm">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            Каталог
                        </a>
                        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            О нас
                        </a>
                        <a href="{{ route('certificates') }}" class="nav-link {{ request()->routeIs('certificates') ? 'active' : '' }}">
                            Сертификаты
                        </a>

                        @auth
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            Корзина ({{ Auth::user()->cart()->count() }})
                        </a>
                        <a href="{{ route('profile.index') }}" class="nav-link">Личный кабинет</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link">Выйти</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="nav-link">Вход</a>
                        <a href="{{ route('register') }}" class="btn-main text-sm">Регистрация</a>
                        @endauth
                    </nav>

                </div>
            </div>
        </header>

        <main class="pt-24">

            <!-- @if(session('success'))
            <div class="max-w-4xl mx-auto px-6 mb-6">
                <div class="border layout-border px-6 py-4 text-green-400 bg-green-900/10">
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="max-w-4xl mx-auto px-6 mb-6">
                <div class="border layout-border px-6 py-4 text-red-400 bg-red-900/10">
                    {{ session('error') }}
                </div>
            </div>
            @endif -->

            <div class="max-w-7xl mx-auto px-6">
                @yield('content')
            </div>

        </main>

        <footer class="border-t layout-border mt-24 bg-[#101010]">
            <div class="max-w-7xl mx-auto px-6 py-16">
                <div class="text-center text-gray-600 text-xs">
                    © {{ date('Y') }} La Vie De Vin · 18+
                </div>
            </div>
        </footer>

    </div>

</body>

</html>
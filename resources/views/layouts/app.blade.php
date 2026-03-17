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
            border-color: rgba(255,255,255,0.06);
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

        .btn-outline {
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.65rem 1.5rem;
            border-radius: 0.375rem;
            color: #e5e5e5;
        }

        .btn-outline:hover {
            border-color: #b91c1c;
            color: #b91c1c;
        }

        .input-clean {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            padding: 0.75rem 0;
            width: 100%;
            color: #e5e5e5;
        }

        .input-clean:focus {
            outline: none;
            border-color: #b91c1c;
        }
    </style>
</head>

<body class="antialiased">

<header class="fixed top-0 left-0 right-0 bg-[#111111] border-b layout-border z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">

            <a href="{{ route('home') }}" class="text-xl tracking-widest font-light text-white">
                LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
            </a>

            <nav class="hidden md:flex items-center space-x-10 text-sm">

                <a href="{{ route('products.index') }}"
                   class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    Каталог
                </a>

                <a href="{{ route('products.new') }}" class="nav-link">Новинки</a>
                <a href="{{ route('products.sale') }}" class="nav-link">Скидки</a>

                @auth
                    <a href="{{ route('cart.index') }}" class="nav-link">
                        Корзина ({{ Auth::user()->cart()->count() }})
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link">
                            Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Вход</a>
                    <a href="{{ route('register') }}" class="btn-main text-sm">
                        Регистрация
                    </a>
                @endauth

            </nav>

            <button class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </div>
</header>

<main class="pt-24">

    @if(session('success'))
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
    @endif

    <div class="max-w-7xl mx-auto px-6">
        @yield('content')
    </div>

</main>

<footer class="border-t layout-border mt-24 bg-[#101010]">
    <div class="max-w-7xl mx-auto px-6 py-16">

        <div class="grid md:grid-cols-4 gap-12 text-sm">

            <div>
                <div class="text-white tracking-widest mb-4">
                    LA VIE <span class="text-[#b91c1c] font-bold">DE VIN</span>
                </div>
                <p class="text-gray-500">
                    Премиальный алкоголь с доставкой по всей стране
                </p>
            </div>

            <div>
                <div class="text-white mb-4">Каталог</div>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:text-[#b91c1c]">Вино</a></li>
                    <li><a href="#" class="hover:text-[#b91c1c]">Виски</a></li>
                    <li><a href="#" class="hover:text-[#b91c1c]">Коньяк</a></li>
                    <li><a href="#" class="hover:text-[#b91c1c]">Шампанское</a></li>
                </ul>
            </div>

            <div>
                <div class="text-white mb-4">Покупателям</div>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:text-[#b91c1c]">Доставка</a></li>
                    <li><a href="#" class="hover:text-[#b91c1c]">Оплата</a></li>
                    <li><a href="#" class="hover:text-[#b91c1c]">Возврат</a></li>
                </ul>
            </div>

            <div>
                <div class="text-white mb-4">Контакты</div>
                <ul class="space-y-2 text-gray-500">
                    <li>+375 (33) 659 72 34</li>
                    <li>matunechkaOpasniy.ru</li>
                </ul>
            </div>

        </div>

        <div class="border-t layout-border mt-12 pt-8 text-center text-gray-600 text-xs">
            © {{ date('Y') }} La Vie De Vin · 18+ Только для совершеннолетних
        </div>

    </div>
</footer>

</body>
</html>
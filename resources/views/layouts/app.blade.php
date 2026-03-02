<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'La Vie De Vin') - Премиальный алкоголь</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Дополнительные стили -->
    <style>
        body {
            background-color: #1a1a1a; /* Глубокий антрацит */
            color: #e5e5e5;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Плавные переходы */
        a, button {
            transition: all 0.2s ease;
        }
        
        /* Кастомные стили для премиум-эффектов */
        .premium-border {
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .premium-card {
            background: #242424; /* Вторичный фон */
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(220, 38, 38, 0.2);
        }
        
        .btn-primary {
            background: #b91c1c; /* Насыщенный красный */
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(185, 28, 28, 0.3);
        }
        
        .btn-outline {
            background: transparent;
            color: #e5e5e5;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }
        
        .btn-outline:hover {
            border-color: #b91c1c;
            color: #b91c1c;
            transform: translateY(-2px);
        }
        
        .nav-link {
            color: #e5e5e5;
            position: relative;
            padding-bottom: 0.25rem;
        }
        
        .nav-link:hover {
            color: #b91c1c;
        }
        
        .nav-link.active {
            color: #b91c1c;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #b91c1c;
        }
        
        .input-premium {
            background: #242424;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e5e5e5;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            width: 100%;
        }
        
        .input-premium:focus {
            outline: none;
            border-color: #b91c1c;
            box-shadow: 0 0 0 2px rgba(185, 28, 28, 0.2);
        }
        
        .input-premium::placeholder {
            color: #6b7280;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Навигация -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#1a1a1a]/80 backdrop-blur-md border-b border-white/5">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Логотип -->
                <a href="{{ route('home') }}" class="text-2xl font-light tracking-wider text-white">
                    LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
                </a>
                
                <!-- Меню -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        Каталог
                    </a>
                    <a href="{{ route('products.new') }}" class="nav-link">
                        Новинки
                    </a>
                    <a href="{{ route('products.sale') }}" class="nav-link">
                        Скидки
                    </a>
                    
                    @auth
                        <a href="{{ route('cart.index') }}" class="nav-link relative">
                            Корзина
                            @if(Auth::user()->cart()->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-[#b91c1c] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                    {{ Auth::user()->cart()->count() }}
                                </span>
                            @endif
                        </a>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-400">{{ Auth::user()->getInitials() }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-[#b91c1c]">
                                    Выйти
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Вход</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-6">Регистрация</a>
                    @endauth
                </div>
                
                <!-- Мобильное меню (упрощенно) -->
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Основной контент -->
    <main class="pt-20">
        @if(session('success'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-green-900/20 border border-green-900 text-green-400 px-6 py-4 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-red-900/20 border border-red-900 text-red-400 px-6 py-4 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Футер -->
    <footer class="bg-[#141414] border-t border-white/5 mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-white font-semibold mb-4">LA VIE DE VIN</h4>
                    <p class="text-gray-400 text-sm">Премиальный алкоголь с доставкой по всей стране</p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Каталог</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Вино</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Виски</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Коньяк</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Шампанское</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Покупателям</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Доставка</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Оплата</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#b91c1c]">Возврат</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Контакты</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="text-gray-400">+375 (33) 659 72 34</li>
                        <li class="text-gray-400">matunechkaOpasniy.ru</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/5 mt-8 pt-8 text-center text-gray-500 text-sm">
                © {{ date('Y') }} La Vie De Vin. 18+ Только для совершеннолетних
            </div>
        </div>
    </footer>
</body>
</html>
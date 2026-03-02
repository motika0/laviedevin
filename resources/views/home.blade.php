@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<!-- Hero секция -->
<div class="relative h-screen max-h-[800px] overflow-hidden">
    <!-- Фоновое изображение с затемнением -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1516598911061-5f47b5ba5c4e?q=80&w=2070" 
             alt="Вино" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#1a1a1a] via-[#1a1a1a]/50 to-transparent"></div>
    </div>
    
    <!-- Контент hero -->
    <div class="relative container mx-auto px-4 h-full flex items-center">
        <div class="max-w-2xl">
            <h1 class="text-5xl md:text-7xl font-light text-white mb-4">
                Искусство 
                <span class="block font-bold text-[#b91c1c] mt-2">винной культуры</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Откройте для себя коллекцию премиальных вин со всего мира. 
                Тщательно отобранные позиции для ценителей.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products.index') }}" class="btn-primary text-center">
                    Перейти в каталог
                </a>
                <a href="#about" class="btn-outline text-center">
                    Узнать больше
                </a>
            </div>
        </div>
    </div>
    
    <!-- Скролл индикатор -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7m14-6l-7 7-7-7"></path>
        </svg>
    </div>
</div>

<!-- Новинки -->
<section class="container mx-auto px-4 py-20">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-4xl font-light text-white mb-2">Новинки</h2>
            <p class="text-gray-400">Пополнение нашей коллекции</p>
        </div>
        <a href="{{ route('products.new') }}" class="text-[#b91c1c] hover:text-white transition">
            Смотреть все →
        </a>
    </div>
    
    @php
        $newProducts = App\Models\Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
            <div class="premium-card rounded-lg overflow-hidden">
                <div class="h-80 overflow-hidden">
                    <img src="{{ $product->getImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover hover:scale-105 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-white mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $product->country }} · {{ $product->volume }}л · {{ $product->alcohol }}%</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-white">{{ number_format($product->price, 0, '', ' ') }} ₽</span>
                        @auth
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="text-[#b91c1c] hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-[#b91c1c] hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- О бренде -->
<section id="about" class="bg-[#141414] py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-light text-white mb-6">
                    Философия <span class="font-bold text-[#b91c1c]">La Vie De Vin</span>
                </h2>
                <p class="text-gray-300 text-lg mb-6">
                    Мы верим, что хорошее вино — это не просто напиток, это история, 
                    застывшая в каждой капле. Наша миссия — находить и привозить лучшие 
                    образцы виноделия со всего мира.
                </p>
                <p class="text-gray-400 mb-8">
                    Каждая бутылка в нашей коллекции проходит строгий отбор, чтобы 
                    предложить вам только самые достойные экземпляры.
                </p>
                <a href="#" class="text-[#b91c1c] hover:text-white transition font-semibold">
                    Подробнее о нас →
                </a>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=1887" 
                     alt="Виноградники"
                     class="rounded-lg shadow-2xl">
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#b91c1c]/10 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</section>
@endsection
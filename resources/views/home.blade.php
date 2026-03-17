@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<!-- ==================== HERO СЕКЦИЯ ==================== -->
<div class="relative h-screen max-h-[800px] overflow-hidden">
    <!-- Фоновое изображение с затемнением -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1516598911061-5f47b5ba5c4e?q=80&w=2070" 
             alt="Вино" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#111111] via-[#111111]/70 to-transparent"></div>
    </div>
    
    <!-- Контент hero -->
    <div class="relative max-w-7xl mx-auto px-6 h-full flex items-center">
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
                <a href="{{ route('products.index') }}" 
                   class="bg-[#b91c1c] text-white px-8 py-3 hover:bg-[#991b1b] transition text-center">
                    Перейти в каталог
                </a>
                <a href="#about" 
                   class="border border-white/20 px-8 py-3 hover:border-[#b91c1c] hover:text-[#b91c1c] transition text-center">
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

<!-- ==================== БЛОК НОВИНОК ==================== -->
<section class="max-w-7xl mx-auto px-6 py-24">
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
        // Получаем 4 последних активных товара
        $newProducts = App\Models\Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
            <div class="bg-[#1a1a1a] border border-white/5 rounded-lg overflow-hidden group hover:border-[#b91c1c]/20 hover:shadow-xl transition-all duration-300">
                <!-- Фото товара -->
                <div class="h-64 overflow-hidden">
                    <img src="{{ $product->getImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                </div>
                
                <!-- Информация о товаре -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $product->name }}</h3>
                    
                    <p class="text-gray-400 text-sm mb-4">
                        {{ $product->country }} · {{ $product->volume }}л · {{ $product->alcohol }}%
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-white">
                            {{ number_format($product->price, 0, '', ' ') }} ₽
                        </span>
                        
                        @auth
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="text-[#b91c1c] hover:text-white transition p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-[#b91c1c] hover:text-white transition p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- ==================== БЛОК О БРЕНДЕ ==================== -->
<section id="about" class="bg-[#0a0a0a] py-24">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Текст -->
            <div>
                <h2 class="text-4xl font-light text-white mb-6">
                    Философия <span class="font-bold text-[#b91c1c]">La Vie De Vin</span>
                </h2>
                <p class="text-gray-300 text-lg mb-6 leading-relaxed">
                    Мы верим, что хорошее вино — это не просто напиток, это история, 
                    застывшая в каждой капле. Наша миссия — находить и привозить лучшие 
                    образцы виноделия со всего мира.
                </p>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    Каждая бутылка в нашей коллекции проходит строгий отбор, чтобы 
                    предложить вам только самые достойные экземпляры. Мы лично посещаем 
                    винодельни, знакомимся с производителями и выбираем только то, 
                    что действительно заслуживает вашего внимания.
                </p>
                <a href="#" class="text-[#b91c1c] hover:text-white transition font-medium inline-flex items-center">
                    Подробнее о нас 
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Фото -->
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=1887" 
                     alt="Виноградники"
                     class="rounded-lg shadow-2xl border border-white/10">
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#b91c1c]/10 rounded-full blur-3xl"></div>
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#b91c1c]/5 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</section>
@endsection
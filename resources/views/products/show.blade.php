@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Хлебные крошки -->
    <div class="text-sm text-gray-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-[#b91c1c]">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#b91c1c]">Каталог</a>
        <span class="mx-2">/</span>
        <span class="text-white">{{ $product->name }}</span>
    </div>
    
    <!-- Детальная информация -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Изображение -->
        <div class="premium-card rounded-lg overflow-hidden">
            <img src="{{ $product->getImageUrl() }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-auto">
        </div>
        
        <!-- Информация -->
        <div>
            <h1 class="text-4xl font-light text-white mb-4">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                <span class="px-3 py-1 bg-white/5 rounded">{{ $product->country }}</span>
                <span class="px-3 py-1 bg-white/5 rounded">{{ $product->volume }} л</span>
                <span class="px-3 py-1 bg-white/5 rounded">{{ $product->alcohol }}%</span>
            </div>
            
            <div class="mb-8">
                @if($product->hasDiscount())
                    <span class="text-gray-500 line-through text-xl">
                        {{ number_format($product->price, 0, '', ' ') }} ₽
                    </span>
                    <span class="text-4xl font-bold text-white ml-4">
                        {{ number_format($product->getCurrentPrice(), 0, '', ' ') }} ₽
                    </span>
                @else
                    <span class="text-4xl font-bold text-white">
                        {{ number_format($product->price, 0, '', ' ') }} ₽
                    </span>
                @endif
            </div>
            
            <div class="prose prose-invert max-w-none mb-8">
                <h3 class="text-xl font-semibold text-white mb-4">Описание</h3>
                <p class="text-gray-300">{{ $product->description }}</p>
            </div>
            
            @auth
                <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div>
                        <input type="number" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $product->stock }}"
                               class="input-premium w-24 text-center">
                    </div>
                    
                    <button type="submit" class="btn-primary flex-1">
                        Добавить в корзину
                    </button>
                </form>
                
                @if(!$product->inStock())
                    <p class="text-red-400 mt-4">Нет в наличии</p>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-primary inline-block">
                    Войдите, чтобы купить
                </a>
            @endauth
            
            <!-- Характеристики -->
            <div class="mt-12 grid grid-cols-2 gap-4">
                <div class="bg-white/5 p-4 rounded-lg">
                    <div class="text-gray-400 text-sm">Страна</div>
                    <div class="text-white font-semibold">{{ $product->country }}</div>
                </div>
                <div class="bg-white/5 p-4 rounded-lg">
                    <div class="text-gray-400 text-sm">Объем</div>
                    <div class="text-white font-semibold">{{ $product->volume }} л</div>
                </div>
                <div class="bg-white/5 p-4 rounded-lg">
                    <div class="text-gray-400 text-sm">Крепость</div>
                    <div class="text-white font-semibold">{{ $product->alcohol }}%</div>
                </div>
                <div class="bg-white/5 p-4 rounded-lg">
                    <div class="text-gray-400 text-sm">В наличии</div>
                    <div class="text-white font-semibold">{{ $product->stock }} шт</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Похожие товары -->
    @if($product->getRelatedProducts()->isNotEmpty())
        <section class="mt-20">
            <h2 class="text-3xl font-light text-white mb-8">Похожие товары</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($product->getRelatedProducts(4) as $related)
                    <div class="premium-card rounded-lg overflow-hidden group">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $related->getImageUrl() }}" 
                                 alt="{{ $related->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-white mb-2">{{ $related->name }}</h3>
                            <div class="flex items-center justify-between">
                                <span class="text-[#b91c1c]">{{ number_format($related->price, 0, '', ' ') }} ₽</span>
                                <a href="{{ route('products.show', $related->id) }}" class="text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('title', 'Каталог')
@section('title', request()->routeIs('products.new') ? 'Новинки' : 'Каталог')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Заголовок -->
    <div class="mb-12">
        <h1 class="text-5xl font-light text-white mb-4">
            {{ request()->routeIs('products.new') ? 'Новинки' : 'Каталог' }}
        </h1>
        <p class="text-gray-400 text-lg">
            @if(request()->routeIs('products.new'))
                Свежие поступления в нашу коллекцию
            @else
                Изысканные вина со всего мира
            @endif
        </p>
    </div>
    
    <!-- Сетка товаров -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @php
            $products = App\Models\Product::where('is_active', true)->paginate(12);
        @endphp
        
        @foreach($products as $product)
            <div class="premium-card rounded-lg overflow-hidden group">
                <div class="h-64 overflow-hidden relative">
                    <img src="{{ $product->getImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    
                    @if($product->hasDiscount())
                        <div class="absolute top-4 right-4 bg-[#b91c1c] text-white px-2 py-1 text-sm rounded">
                            -{{ $product->getDiscountPercent() }}%
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $product->name }}</h3>
                    
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                        <span>{{ $product->country }}</span>
                        <span>•</span>
                        <span>{{ $product->volume }} л</span>
                        <span>•</span>
                        <span>{{ $product->alcohol }}%</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->hasDiscount())
                                <span class="text-gray-500 line-through text-sm">
                                    {{ number_format($product->price, 0, '', ' ') }} ₽
                                </span>
                                <span class="text-2xl font-bold text-white block">
                                    {{ number_format($product->getCurrentPrice(), 0, '', ' ') }} ₽
                                </span>
                            @else
                                <span class="text-2xl font-bold text-white">
                                    {{ number_format($product->price, 0, '', ' ') }} ₽
                                </span>
                            @endif
                        </div>
                        
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="text-[#b91c1c] hover:text-white transition p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Пагинация -->
    <div class="mt-12">
        {{ $products->links() }}
    </div>
</div>
@endsection
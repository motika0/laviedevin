@extends('layouts.app')

@section('title', 'Новинки')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Новинки</h1>
    
    @if($products->isEmpty())
        <p class="text-gray-600">Новинок пока нет</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="{{ $product->getImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="text-gray-900 hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-2">
                            {{ $product->country }} | {{ $product->volume }} л | {{ $product->alcohol }}%
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->hasDiscount())
                                    <span class="text-gray-400 line-through text-sm">
                                        {{ number_format($product->price, 0, '', ' ') }} ₽
                                    </span>
                                    <span class="text-red-600 font-bold text-lg ml-2">
                                        {{ number_format($product->getCurrentPrice(), 0, '', ' ') }} ₽
                                    </span>
                                @else
                                    <span class="text-gray-900 font-bold text-lg">
                                        {{ number_format($product->price, 0, '', ' ') }} ₽
                                    </span>
                                @endif
                            </div>
                            
                            @auth
                                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        В корзину
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                    Войти
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
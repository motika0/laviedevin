@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-light text-white mb-8">Корзина</h1>

    @php
        $cartItems = Auth::user()->cart()->with('product')->get();
        $total = Auth::user()->getCartTotal();
        $bonusPoints = Auth::user()->getBonusPoints();
        $bonusDiscount = session('bonus_discount', 0);
        $finalTotal = $total - $bonusDiscount;
    @endphp

    @if($cartItems->isEmpty())
        <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h2 class="text-2xl text-white mb-4">Ваша корзина пуста</h2>
            <p class="text-gray-400 mb-8">Но это никогда не поздно исправить :)</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-[#b91c1c] text-white px-8 py-3 hover:bg-[#991b1b] transition">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                    <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4 flex gap-4 items-center">
                        <div class="w-24 h-24 bg-[#111111] rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $item->product->getImageUrl() }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-white font-semibold mb-1">
                                <a href="{{ route('products.show', $item->product->id) }}" 
                                   class="hover:text-[#b91c1c] transition">
                                    {{ $item->product->name }}
                                </a>
                            </h3>
                            <p class="text-gray-400 text-sm mb-2">
                                {{ $item->product->volume }} л · {{ $item->product->alcohol }}%
                            </p>
                            <p class="text-[#b91c1c] font-bold">
                                {{ number_format($item->product->getCurrentPrice(), 0, '', ' ') }} ₽ / шт
                            </p>
                        </div>
                        
                        <div class="w-24">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="update-cart-form">
                                @csrf
                                @method('PUT')
                                <input type="number" 
                                       name="quantity" 
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       max="{{ $item->product->stock }}"
                                       class="w-full bg-[#111111] border border-white/10 rounded-lg px-3 py-2 text-white focus:border-[#b91c1c] focus:outline-none"
                                       onchange="this.form.submit()">
                            </form>
                        </div>
                        
                        <div class="w-24 text-right">
                            <span class="text-white font-bold">
                                {{ number_format($item->getTotal(), 0, '', ' ') }} ₽
                            </span>
                        </div>
                        

                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-gray-400 hover:text-[#b91c1c] transition p-2"
                                    onclick="return confirm('Удалить товар из корзины?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
                
                <div class="text-right">
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-gray-400 hover:text-[#b91c1c] transition text-sm"
                                onclick="return confirm('Очистить корзину?')">
                            Очистить корзину
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                @if($bonusPoints > 0)
                    <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6 mb-6">
                        <h3 class="text-white font-semibold mb-4">Ваши бонусы</h3>
                        <p class="text-gray-400 mb-3">Доступно: <span class="text-[#b91c1c] font-bold">{{ $bonusPoints }}</span> баллов</p>
                        
                        @if(!session('bonus_discount'))
                            <form action="{{ route('cart.apply-bonus') }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="number" 
                                           name="bonus_points" 
                                           max="{{ $bonusPoints }}"
                                           min="0"
                                           placeholder="Сколько списать?"
                                           class="flex-1 bg-[#111111] border border-white/10 rounded-lg px-3 py-2 text-white focus:border-[#b91c1c] focus:outline-none">
                                    <button type="submit" 
                                            class="bg-[#b91c1c] text-white px-4 py-2 rounded-lg hover:bg-[#991b1b] transition">
                                        Применить
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="flex items-center justify-between">
                                <span class="text-green-400">Скидка {{ session('bonus_discount') }} ₽ применена</span>
                                <form action="{{ route('cart.remove-bonus') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-[#b91c1c] text-sm">
                                        Отменить
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
                

                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6">
                    <h3 class="text-white font-semibold mb-4">Ваш заказ</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-400">
                            <span>Товары ({{ $cartItems->sum('quantity') }} шт)</span>
                            <span>{{ number_format($total, 0, '', ' ') }} ₽</span>
                        </div>
                        
                        @if($bonusDiscount > 0)
                            <div class="flex justify-between text-green-400">
                                <span>Скидка бонусами</span>
                                <span>-{{ number_format($bonusDiscount, 0, '', ' ') }} ₽</span>
                            </div>
                        @endif
                        
                        <div class="border-t border-white/10 my-3 pt-3 flex justify-between text-white font-bold text-lg">
                            <span>Итого</span>
                            <span>{{ number_format($finalTotal, 0, '', ' ') }} ₽</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('orders.checkout') }}" 
                       class="block w-full bg-[#b91c1c] text-white text-center px-6 py-3 rounded-lg hover:bg-[#991b1b] transition font-medium">
                        Оформить заказ
                    </a>
                    
                    <p class="text-xs text-gray-500 text-center mt-4">
                        Нажимая кнопку, вы подтверждаете, что вам есть 18 лет
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
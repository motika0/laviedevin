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
        
        $conversionRate = Auth::user()->loyalty?->getConversionRate() ?? 0.5;
        $maxBonusValue = floor($total * 0.5);
        $maxBonusPoints = floor($maxBonusValue / $conversionRate);
        $maxBonusPoints = min($bonusPoints, $maxBonusPoints);
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
                    <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4 flex gap-4" data-price="{{ $item->product->getCurrentPrice() }}">
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
                                {{ number_format($item->product->getCurrentPrice(), 0, '', ' ') }} BYN / шт
                            </p>
                        </div>
                        
                        <div class="w-24">
                            <input type="number" 
                                   class="cart-quantity w-full bg-[#111111] border border-white/10 rounded-lg px-3 py-2 text-white focus:border-[#b91c1c] focus:outline-none"
                                   data-cart-id="{{ $item->id }}"
                                   value="{{ $item->quantity }}" 
                                   min="1" 
                                   max="{{ $item->product->stock }}">
                        </div>
                        
                        <div class="w-24 text-right">
                            <span class="item-total text-white font-bold">
                                {{ number_format($item->product->getCurrentPrice() * $item->quantity, 0, '', ' ') }} BYN
                            </span>
                        </div>
                        
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
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
                        <p class="text-gray-400 mb-2">Доступно: <span class="text-[#b91c1c] font-bold">{{ number_format($bonusPoints, 0, '', ' ') }}</span> баллов</p>
                        <p class="text-gray-500 text-sm mb-3">
                            @php
                                $level = Auth::user()->getLoyaltyLevel();
                                $rate = Auth::user()->loyalty?->getConversionRate() ?? 0.5;
                            @endphp
                            Уровень: <span class="text-[#b91c1c]">{{ $level === 'золото' ? 'Золотой' : ($level === 'серебро' ? 'Серебряный' : 'Бронзовый') }}</span><br>
                            1 балл = {{ $rate }} BYN<br>
                            Максимум бонусов: 50% от суммы заказа
                        </p>
                        
                        @if(!session('bonus_discount'))
                            <div class="flex gap-2">
                                <input type="number" 
                                       id="bonus_points"
                                       name="bonus_points" 
                                       max="{{ floor($maxBonusPoints) }}"
                                       min="0"
                                       placeholder="Количество баллов"
                                       class="flex-1 bg-[#111111] border border-white/10 rounded-lg px-3 py-2 text-white focus:border-[#b91c1c] focus:outline-none">
                                <button type="button" 
                                        id="apply-bonus-btn"
                                        class="bg-[#b91c1c] text-white px-4 py-2 rounded-lg hover:bg-[#991b1b] transition">
                                    Применить
                                </button>
                            </div>
                            <p class="text-gray-500 text-xs mt-2">Максимум: {{ number_format(floor($maxBonusPoints), 0, '', ' ') }} баллов ({{ number_format($maxBonusValue, 0, '', ' ') }} BYN)</p>
                        @else
                            <div class="flex items-center justify-between bg-[#111111] p-3 rounded-lg">
                                <div>
                                    <span class="text-green-400">Скидка применена</span>
                                    <p class="text-white text-sm">{{ number_format(session('bonus_discount'), 0, '', ' ') }} BYN</p>
                                    <p class="text-gray-500 text-xs">Использовано: {{ session('bonus_used') }} баллов</p>
                                </div>
                                <button type="button" id="remove-bonus-btn" class="text-gray-400 hover:text-[#b91c1c] text-sm">
                                    Отменить
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
                
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6">
                    <h3 class="text-white font-semibold mb-4">Ваш заказ</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-400">
                            <span>Товары (<span id="total-quantity">{{ $cartItems->sum('quantity') }}</span> шт)</span>
                            <span id="cart-total">{{ number_format($total, 0, '', ' ') }} BYN</span>
                        </div>
                        
                        @if($bonusDiscount > 0)
                            <div class="flex justify-between text-green-400" id="bonus-discount-row">
                                <span>Скидка бонусами</span>
                                <span id="discount-amount">-{{ number_format($bonusDiscount, 0, '', ' ') }} BYN</span>
                            </div>
                        @endif
                        
                        <div class="border-t border-white/10 my-3 pt-3 flex justify-between text-white font-bold text-lg">
                            <span>Итого</span>
                            <span id="final-total">{{ number_format($finalTotal, 0, '', ' ') }} BYN</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-white mb-2">Телефон *</label>
                        <input type="tel" 
                               id="order-phone"
                               value="{{ Auth::user()->phone }}"
                               class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-2 text-white focus:border-[#b91c1c] focus:outline-none">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-white mb-2">Адрес доставки *</label>
                        <textarea id="order-address" 
                                  rows="3"
                                  class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-2 text-white focus:border-[#b91c1c] focus:outline-none"
                                  placeholder="Город, улица, дом, квартира"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-white mb-2">Комментарий к заказу</label>
                        <textarea id="order-comment" 
                                  rows="2"
                                  class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-2 text-white focus:border-[#b91c1c] focus:outline-none"
                                  placeholder="Пожелания к заказу..."></textarea>
                    </div>
                    
                    <div id="order-message" class="hidden mb-4 p-3 rounded-lg bg-[#111111] border border-[#b91c1c]/30 text-center">
                        <p class="text-[#b91c1c] text-sm">Заказ оформлен! Перенаправление через <span id="countdown">10</span> секунд...</p>
                    </div>
                    
                    <button type="button" 
                            id="checkout-btn"
                            class="block w-full bg-[#b91c1c] text-white text-center px-6 py-3 rounded-lg hover:bg-[#991b1b] transition font-medium">
                        Оформить заказ
                    </button>
                    
                    <p class="text-xs text-gray-500 text-center mt-4">
                        Нажимая кнопку, вы подтверждаете, что вам есть 18 лет
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обновление количества товаров
    const quantityInputs = document.querySelectorAll('.cart-quantity');
    
    async function updateQuantity(cartId, newQuantity) {
        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                const itemDiv = document.querySelector(`.cart-quantity[data-cart-id="${cartId}"]`).closest('.flex');
                const itemTotalSpan = itemDiv.querySelector('.item-total');
                const price = parseFloat(itemDiv.dataset.price);
                itemTotalSpan.textContent = Math.floor(price * newQuantity).toLocaleString() + ' BYN';
                
                const totalSpan = document.getElementById('cart-total');
                const finalTotalSpan = document.getElementById('final-total');
                totalSpan.textContent = Math.floor(data.total).toLocaleString() + ' BYN';
                finalTotalSpan.textContent = Math.floor(data.final_total).toLocaleString() + ' BYN';
                
                const totalQuantitySpan = document.getElementById('total-quantity');
                let totalQty = 0;
                document.querySelectorAll('.cart-quantity').forEach(input => {
                    totalQty += parseInt(input.value);
                });
                totalQuantitySpan.textContent = totalQty;
                
                const totalValue = data.total;
                const maxBonusValue = Math.floor(totalValue * 0.5);
                const rate = {{ $conversionRate }};
                const maxBonusPoints = Math.floor(maxBonusValue / rate);
                const bonusPointsInput = document.getElementById('bonus_points');
                if (bonusPointsInput) {
                    bonusPointsInput.max = Math.min(maxBonusPoints, {{ $bonusPoints }});
                }
                const bonusMaxText = document.querySelector('.text-gray-500.text-xs.mt-2');
                if (bonusMaxText) {
                    bonusMaxText.textContent = `Максимум: ${Math.min(maxBonusPoints, {{ $bonusPoints }}).toLocaleString()} баллов (${maxBonusValue.toLocaleString()} BYN)`;
                }
            } else {
                alert(data.message || 'Ошибка при обновлении количества');
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Произошла ошибка');
            location.reload();
        }
    }
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const newQuantity = parseInt(this.value);
            if (newQuantity >= 1) {
                updateQuantity(cartId, newQuantity);
            } else {
                this.value = 1;
                updateQuantity(cartId, 1);
            }
        });
    });
    
    // Применение бонусов
    const applyBonusBtn = document.getElementById('apply-bonus-btn');
    if (applyBonusBtn) {
        applyBonusBtn.addEventListener('click', async function() {
            const bonusPoints = document.getElementById('bonus_points').value;
            
            if (!bonusPoints || bonusPoints <= 0) {
                alert('Введите количество бонусов');
                return;
            }
            
            try {
                const response = await fetch('{{ route("cart.apply-bonus") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ bonus_points: bonusPoints })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка');
            }
        });
    }
    
    // Отмена бонусов
    const removeBonusBtn = document.getElementById('remove-bonus-btn');
    if (removeBonusBtn) {
        removeBonusBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('{{ route("cart.remove-bonus") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка');
            }
        });
    }
    
    // Оформление заказа
    const checkoutBtn = document.getElementById('checkout-btn');
    const orderMessage = document.getElementById('order-message');
    const countdownSpan = document.getElementById('countdown');
    
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', async function() {
            const phone = document.getElementById('order-phone').value;
            const address = document.getElementById('order-address').value;
            const comment = document.getElementById('order-comment').value;
            
            if (!phone) {
                alert('Введите номер телефона');
                return;
            }
            
            if (!address) {
                alert('Введите адрес доставки');
                return;
            }
            
            checkoutBtn.disabled = true;
            checkoutBtn.textContent = 'Оформление...';
            
            try {
                const response = await fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone: phone,
                        address: address,
                        comment: comment
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Показываем сообщение
                    orderMessage.classList.remove('hidden');
                    
                    let seconds = 10;
                    countdownSpan.textContent = seconds;
                    
                    const interval = setInterval(() => {
                        seconds--;
                        countdownSpan.textContent = seconds;
                        
                        if (seconds <= 0) {
                            clearInterval(interval);
                            
                            // Отправляем запрос на выполнение заказа
                            fetch(`/orders/${data.order_id}/process`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                }
                            });
                            
                            // Перезагружаем страницу (корзина уже пустая)
                            location.reload();
                        }
                    }, 1000);
                } else {
                    alert(data.message);
                    checkoutBtn.disabled = false;
                    checkoutBtn.textContent = 'Оформить заказ';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка при оформлении заказа');
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Оформить заказ';
            }
        });
    }
});
</script>
@endsection
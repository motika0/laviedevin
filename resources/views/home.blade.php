@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="relative h-screen max-h-[800px] overflow-hidden">
    <div class="absolute inset-0">
       <img src="{{ asset('images/main.jpg') }}" alt="Вино"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#111111] via-[#111111]/70 to-transparent"></div>
    </div>
    
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
                <a href="{{ route('about') }}" 
                   class="border border-white/20 px-8 py-3 hover:border-[#b91c1c] hover:text-[#b91c1c] transition text-center">
                    О нас
                </a>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7m14-6l-7 7-7-7"></path>
        </svg>
    </div>
</div>

<section class="max-w-7xl mx-auto px-6 py-24">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-4xl font-light text-white mb-2">Популярные товары</h2>
            <p class="text-gray-400">Лучший выбор наших клиентов</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-[#b91c1c] hover:text-white transition">
            Смотреть все →
        </a>
    </div>
    
    @php
        $popularProducts = App\Models\Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($popularProducts as $product)
            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-[#b91c1c]/40 transition-all duration-300">
                <div class="relative h-56 overflow-hidden bg-[#0a0a0a]">
                    <img src="{{ $product->getImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-contain p-4 group-hover:scale-105 transition duration-500">
                    
                    @if($product->hasDiscount() && $product->old_price > $product->price)
                        <div class="absolute top-3 right-3 bg-[#b91c1c] text-white text-xs px-2 py-1 rounded-full">
                            -{{ $product->getDiscountPercent() }}%
                        </div>
                    @endif
                </div>
                
                <div class="p-5">
                    <h3 class="text-lg font-medium text-white mb-1">
                        <a href="{{ route('products.show', $product->id) }}" class="hover:text-[#b91c1c] transition">
                            {{ $product->name }}
                        </a>
                    </h3>
                    
                    <p class="text-gray-500 text-sm mb-3">
                        {{ $product->country }} · {{ $product->volume }}л · {{ $product->alcohol }}%
                    </p>
                    
                    <div class="flex items-center justify-between mt-4">
                        <div>
                            @if($product->hasDiscount() && $product->old_price > $product->price)
                                <span class="text-gray-500 line-through text-sm">
                                    {{ number_format($product->old_price, 0, '', ' ') }} ₽
                                </span>
                                <span class="text-xl font-bold text-white block">
                                    {{ number_format($product->price, 0, '', ' ') }} ₽
                                </span>
                            @else
                                <span class="text-xl font-bold text-white">
                                    {{ number_format($product->price, 0, '', ' ') }} ₽
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @auth
                                <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center hover:border-[#b91c1c] hover:bg-[#b91c1c]/10 transition">
                                        <svg class="w-5 h-5" fill="{{ Auth::user()->hasInFavorites($product->id) ? '#b91c1c' : 'none' }}" 
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center hover:border-[#b91c1c] hover:bg-[#b91c1c]/10 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </a>
                            @endauth
                            
                            @auth
                                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-[#b91c1c] text-white text-sm rounded-lg hover:bg-[#991b1b] transition font-medium">
                                        Купить
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-4 py-2 bg-[#b91c1c] text-white text-sm rounded-lg hover:bg-[#991b1b] transition font-medium">
                                    Купить
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-24">
    <h2 class="text-4xl font-light text-white mb-12 text-center">
        Что говорят <span class="font-bold text-[#b91c1c]">наши клиенты</span>
    </h2>
    
    @php
        $reviews = App\Models\Review::where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    @endphp
    
    @if($reviews->isEmpty())
        <div class="text-center text-gray-500 py-12">
            <p>Пока нет отзывов. Станьте первым!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6 hover:border-[#b91c1c]/30 transition">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-[#b91c1c]/20 flex items-center justify-center">
                            <span class="text-[#b91c1c] font-bold">
                                {{ mb_substr($review->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $review->user->getFullName() }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="w-4 h-4 text-[#b91c1c]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $review->comment }}</p>
                    <p class="text-gray-500 text-xs mt-3">{{ $review->created_at->format('d.m.Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
    
    @auth
        <div class="text-center mt-10">
            <button onclick="toggleReviewForm()" 
                    class="inline-block border border-[#b91c1c] text-[#b91c1c] px-6 py-2 rounded-lg hover:bg-[#b91c1c] hover:text-white transition">
                Оставить отзыв
            </button>
        </div>
    @else
        <div class="text-center mt-10">
            <a href="{{ route('login') }}" class="text-[#b91c1c] hover:text-white transition">
                Войдите, чтобы оставить отзыв
            </a>
        </div>
    @endauth
    
@auth
    <div id="review-form" class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6 mt-6 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-light text-white">Оставить отзыв о товаре</h3>
            <button onclick="toggleReviewForm()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="review-submit-form" action="#" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-white mb-2">Товар</label>
                <select name="product_id" id="product-select" required class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-2 text-white focus:border-[#b91c1c] focus:outline-none">
                    <option value="">Выберите товар</option>
                    @php
                        $purchasedProducts = Auth::user()->orders()
                            ->where('status', 'выполнен')
                            ->with('items.product')
                            ->get()
                            ->pluck('items')
                            ->flatten()
                            ->pluck('product')
                            ->unique('id');
                    @endphp
                    @foreach($purchasedProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-white mb-2">Оценка</label>
                <div class="flex gap-2" id="stars-container">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star cursor-pointer text-3xl text-gray-500 hover:text-[#b91c1c] transition" onclick="setRating({{ $i }})">★</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-white mb-2">Ваш отзыв</label>
                <textarea name="comment" rows="4" required 
                          class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-2 text-white focus:border-[#b91c1c] focus:outline-none"
                          placeholder="Поделитесь впечатлениями о товаре..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-[#b91c1c] text-white px-6 py-2 rounded-lg hover:bg-[#991b1b] transition">
                    Отправить отзыв
                </button>
                <button type="button" onclick="toggleReviewForm()" class="border border-white/20 text-white px-6 py-2 rounded-lg hover:border-[#b91c1c] hover:text-[#b91c1c] transition">
                    Отмена
                </button>
            </div>
        </form>
    </div>
    
    <script>
        function toggleReviewForm() {
            const form = document.getElementById('review-form');
            form.classList.toggle('hidden');
            
            if (!form.classList.contains('hidden')) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        function setRating(rating) {
            document.getElementById('rating-input').value = rating;
            
            const stars = document.querySelectorAll('#stars-container .star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-[#b91c1c]');
                    star.classList.remove('text-gray-500');
                } else {
                    star.classList.add('text-gray-500');
                    star.classList.remove('text-[#b91c1c]');
                }
            });
        }
        
        // При выборе товара - меняем action формы
        const productSelect = document.getElementById('product-select');
        const reviewForm = document.getElementById('review-submit-form');
        
        productSelect.addEventListener('change', function() {
            if (this.value) {
                reviewForm.action = '/reviews/product/' + this.value;
            } else {
                reviewForm.action = '#';
            }
        });
    </script>
    @endauth
</section>
@endsection
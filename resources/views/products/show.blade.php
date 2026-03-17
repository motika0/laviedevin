@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-[#b91c1c] transition">Главная</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#b91c1c] transition">Каталог</a>
        <span>/</span>
        <span class="text-white">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <div class="bg-[#1a1a1a] border border-white/5 rounded-lg overflow-hidden">
            <img src="{{ $product->getImageUrl() }}"
                alt="{{ $product->name }}"
                class="w-full h-auto">
        </div>

        <div>
            <h1 class="text-4xl font-light text-white mb-4">{{ $product->name }}</h1>

            <div class="flex flex-wrap gap-3 mb-6">
                <span class="px-3 py-1 bg-white/5 rounded-full text-sm text-gray-300">{{ $product->country }}</span>
                <span class="px-3 py-1 bg-white/5 rounded-full text-sm text-gray-300">{{ $product->volume }} л</span>
                <span class="px-3 py-1 bg-white/5 rounded-full text-sm text-gray-300">{{ $product->alcohol }}%</span>
            </div>

            <div class="mb-6">
                @if($product->hasDiscount())
                <span class="text-gray-500 line-through text-xl">
                    {{ number_format($product->price, 0, '', ' ') }} ₽
                </span>
                <span class="text-4xl font-bold text-white ml-4">
                    {{ number_format($product->getCurrentPrice(), 0, '', ' ') }} ₽
                </span>
                <span class="ml-4 text-[#b91c1c] text-sm">
                    -{{ $product->getDiscountPercent() }}%
                </span>
                @else
                <span class="text-4xl font-bold text-white">
                    {{ number_format($product->price, 0, '', ' ') }} ₽
                </span>
                @endif
            </div>

            <div class="mb-8">
                <h3 class="text-white font-semibold mb-3">Описание</h3>
                <p class="text-gray-400 leading-relaxed">{{ $product->description }}</p>
            </div>

            <div class="space-y-4">
                @auth
                <div class="flex gap-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1 flex gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="w-32">
                            <input type="number"
                                name="quantity"
                                value="1"
                                min="1"
                                max="{{ $product->stock }}"
                                class="w-full bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 text-white focus:border-[#b91c1c] focus:outline-none"
                                {{ $product->stock < 1 ? 'disabled' : '' }}>
                        </div>

                        <button type="submit"
                            class="flex-1 bg-[#b91c1c] text-white px-8 py-3 hover:bg-[#991b1b] transition text-center font-medium"
                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                            {{ $product->stock < 1 ? 'Нет в наличии' : 'Добавить в корзину' }}
                        </button>
                    </form>

                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-14 h-14 border border-white/10 rounded-lg hover:border-[#b91c1c] transition flex items-center justify-center">
                            <svg class="w-6 h-6" fill="{{ Auth::user()->hasInFavorites($product->id) ? '#b91c1c' : 'none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                @if($product->stock < 1)
                    <p class="text-red-400 text-sm mt-2">Товар временно отсутствует на складе</p>
                    @endif
                    @else
                    <a href="{{ route('login') }}"
                        class="inline-block bg-[#b91c1c] text-white px-8 py-3 hover:bg-[#991b1b] transition">
                        Войдите, чтобы купить
                    </a>
                    @endauth
            </div>

            <div class="grid grid-cols-2 gap-4 mt-8">
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4">
                    <div class="text-gray-400 text-sm mb-1">Страна</div>
                    <div class="text-white font-medium">{{ $product->country }}</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4">
                    <div class="text-gray-400 text-sm mb-1">Объем</div>
                    <div class="text-white font-medium">{{ $product->volume }} л</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4">
                    <div class="text-gray-400 text-sm mb-1">Крепость</div>
                    <div class="text-white font-medium">{{ $product->alcohol }}%</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-4">
                    <div class="text-gray-400 text-sm mb-1">В наличии</div>
                    <div class="text-white font-medium">{{ $product->stock }} шт</div>
                </div>
            </div>
        </div>
    </div>

    @php
    $relatedProducts = App\Models\Product::where('category_id', $product->category_id)
    ->where('id', '!=', $product->id)
    ->where('is_active', true)
    ->limit(4)
    ->get();
    @endphp

    @if($relatedProducts->isNotEmpty())
    <section class="mt-16">
        <h2 class="text-3xl font-light text-white mb-8">Похожие товары</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-[#1a1a1a] border border-white/5 rounded-lg overflow-hidden group hover:border-[#b91c1c]/30 transition-all">
                <div class="h-48 overflow-hidden">
                    <a href="{{ route('products.show', $related->id) }}">
                        <img src="{{ $related->getImageUrl() }}"
                            alt="{{ $related->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-white mb-1">
                        <a href="{{ route('products.show', $related->id) }}" class="hover:text-[#b91c1c]">
                            {{ $related->name }}
                        </a>
                    </h3>
                    <p class="text-gray-400 text-xs mb-3">
                        {{ $related->country }} · {{ $related->volume }}л · {{ $related->alcohol }}%
                    </p>

                    <div class="flex items-center justify-between">
                        <span class="text-[#b91c1c] font-bold">
                            {{ number_format($related->getCurrentPrice(), 0, '', ' ') }} ₽
                        </span>

                        <div class="flex items-center gap-2">
                            @auth
                            <form action="{{ route('favorites.toggle', $related->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-[#b91c1c] transition">
                                    <svg class="w-5 h-5" fill="{{ Auth::user()->hasInFavorites($related->id) ? '#b91c1c' : 'none' }}"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="text-gray-400 hover:text-[#b91c1c] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </a>
                            @endauth

                            <a href="{{ route('products.show', $related->id) }}" class="text-gray-400 hover:text-[#b91c1c] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;

        fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Ошибка при добавлении в корзину');
                }
            });
    }
</script>
@endpush
@endsection
@push('scripts')
<script>
    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;

        fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Ошибка');
                }
            });
    }
</script>
@endpush
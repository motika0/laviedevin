@extends('layouts.app')

@section('title', request()->routeIs('products.new') ? 'Новинки' : (request()->routeIs('products.sale') ? 'Скидки' : 'Каталог'))

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- ==================== ЗАГОЛОВОК ==================== -->
    <div class="mb-12">
        <h1 class="text-5xl font-light text-white mb-4">
            {{ request()->routeIs('products.new') ? 'Новинки' : (request()->routeIs('products.sale') ? 'Скидки' : 'Каталог') }}
        </h1>
        <p class="text-gray-400 text-lg">
            {{ request()->routeIs('products.new') 
                ? 'Свежие поступления в нашу коллекцию' 
                : (request()->routeIs('products.sale') 
                    ? 'Лучшие предложения со скидками' 
                    : 'Изысканные вина со всего мира') }}
        </p>
    </div>

    <!-- ==================== ФИЛЬТРЫ, СОРТИРОВКА И ПОИСК ==================== -->
    <div class="mb-8 space-y-4">
        <!-- Строка поиска -->
        <div class="relative">
            <form method="GET" action="{{ route('products.index') }}" id="search-form">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Поиск по названию или стране..." 
                       class="w-full bg-[#1a1a1a] border border-white/10 rounded-lg px-6 py-4 text-white placeholder-gray-500 focus:border-[#b91c1c] focus:outline-none transition">
                <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#b91c1c]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Фильтры и сортировка -->
        <div class="flex flex-wrap gap-4">
            <!-- Фильтр по категориям -->
            <select name="category" form="search-form" class="bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 text-white focus:border-[#b91c1c] focus:outline-none">
                <option value="">Все категории</option>
                @php
                    $categories = App\Models\Category::all();
                @endphp
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <!-- Фильтр по стране -->
            <select name="country" form="search-form" class="bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 text-white focus:border-[#b91c1c] focus:outline-none">
                <option value="">Все страны</option>
                @php
                    $countries = App\Models\Product::where('is_active', true)->distinct()->pluck('country');
                @endphp
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>

            <!-- Сортировка -->
            <select name="sort" form="search-form" class="bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 text-white focus:border-[#b91c1c] focus:outline-none">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новинки</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена (по возрастанию)</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена (по убыванию)</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название (А-Я)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Название (Я-А)</option>
            </select>

            <!-- Кнопка сброса фильтров -->
            @if(request()->anyFilled(['search', 'category', 'country', 'sort']))
                <a href="{{ route('products.index') }}" class="bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 text-white hover:border-[#b91c1c] transition">
                    Сбросить фильтры
                </a>
            @endif
        </div>
    </div>

    <!-- ==================== СЕТКА ТОВАРОВ ==================== -->
    @php
        $query = App\Models\Product::where('is_active', true);
        
        // Поиск
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Фильтр по категории
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        
        // Фильтр по стране
        if (request('country')) {
            $query->where('country', request('country'));
        }
        
        // Сортировка
        switch(request('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12)->withQueryString();
    @endphp

    @if($products->isEmpty())
        <div class="text-center py-20">
            <p class="text-gray-400 text-lg mb-4">Товары не найдены</p>
            <a href="{{ route('products.index') }}" class="text-[#b91c1c] hover:text-white transition">
                Сбросить фильтры
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-[#1a1a1a] border border-white/5 rounded-lg overflow-hidden group hover:border-[#b91c1c]/30 hover:shadow-xl transition-all duration-300">
                    <!-- Изображение с метками -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $product->getImageUrl() }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        <!-- Метка новинки -->
                        @if($product->created_at->diffInDays(now()) < 30)
                            <div class="absolute top-4 left-4 bg-[#b91c1c] text-white text-xs px-2 py-1 rounded">
                                NEW
                            </div>
                        @endif
                        
                        <!-- Метка скидки -->
                        @if($product->hasDiscount())
                            <div class="absolute top-4 right-4 bg-black/80 text-white text-xs px-2 py-1 rounded border border-[#b91c1c]">
                                -{{ $product->getDiscountPercent() }}%
                            </div>
                        @endif
                    </div>
                    
                    <!-- Информация о товаре -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white mb-2">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-[#b91c1c] transition">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-400 text-sm mb-4">
                            {{ $product->country }} · {{ $product->volume }}л · {{ $product->alcohol }}%
                        </p>
                        
                        <!-- Цена и действия -->
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
                            
                            <div class="flex items-center gap-2">
                                <!-- Кнопка избранного (лайк) -->
                                @auth
                                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-[#b91c1c] transition">
                                            <svg class="w-6 h-6" fill="{{ Auth::user()->hasInFavorites($product->id) ? '#b91c1c' : 'none' }}" 
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-[#b91c1c] transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </a>
                                @endauth
                                
                                <!-- Кнопка просмотра -->
                                <a href="{{ route('products.show', $product->id) }}" 
                                   class="text-gray-400 hover:text-[#b91c1c] transition p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- ==================== ПАГИНАЦИЯ ==================== -->
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Стили для пагинации */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    .pagination .page-item {
        list-style: none;
    }
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.1);
        color: #e5e5e5;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    .pagination .page-link:hover {
        border-color: #b91c1c;
        color: #b91c1c;
    }
    .pagination .active .page-link {
        background: #b91c1c;
        border-color: #b91c1c;
        color: white;
    }
</style>
@endpush
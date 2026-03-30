@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-light text-white mb-10">
        Личный кабинет
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="space-y-6">

            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-white/10">
                <h2 class="text-xl text-white mb-4">Мои данные</h2>

                <p class="text-gray-400 text-sm">ФИО</p>
                <p class="text-white mb-3">{{ $user->getFullName() }}</p>

                <p class="text-gray-400 text-sm">Email</p>
                <p class="text-white mb-3">{{ $user->email }}</p>

                <p class="text-gray-400 text-sm">Телефон</p>
                <p class="text-white mb-3">{{ $user->phone }}</p>

                <p class="text-gray-400 text-sm">Дата рождения</p>
                <p class="text-white mb-3">{{ $user->birth_date?->format('d.m.Y') }}</p>

                <p class="text-gray-400 text-sm">Бонусы</p>
                <p class="text-[#b91c1c] font-bold">
                    {{ $user->getBonusPoints() }} баллов
                </p>
            </div>

            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-white/10">

                <h2 class="text-xl text-white mb-4">Подтверждение возраста</h2>

                @if($user->ageVerification && $user->ageVerification->isVerified())
                    <div class="bg-green-900/20 border border-green-700 p-4 rounded-lg">
                        <p class="text-green-400 font-medium">
                            ✅ Возраст подтвержден
                        </p>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $user->ageVerification->getVerifiedAtFormatted() }}
                        </p>
                    </div>
                @else
                    <form action="{{ route('profile.verify-age') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="document" required
                               class="w-full mb-4 text-white">

                        <button class="w-full bg-[#b91c1c] py-2 rounded-lg hover:bg-[#991b1b]">
                            Загрузить документ
                        </button>
                    </form>
                @endif

            </div>

        </div>

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-white/10">
                <h2 class="text-xl text-white mb-4">Мои заказы</h2>

                @forelse($user->orders as $order)
                    <div class="border-b border-white/10 py-3">
                        <p class="text-white">
                            Заказ #{{ $order->id }} — {{ $order->status }}
                        </p>
                        <p class="text-gray-400 text-sm">
                            {{ $order->created_at->format('d.m.Y') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400">Нет заказов</p>
                @endforelse
            </div>

            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-white/10">
                <h2 class="text-xl text-white mb-4">Избранное</h2>

                <div class="grid grid-cols-2 gap-4">
                    @forelse($user->favorites as $fav)
                        <div class="border border-white/10 p-3 rounded-lg">
                            <p class="text-white text-sm">
                                {{ $fav->product->name }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-400">Пусто</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-white/10">
                <h2 class="text-xl text-white mb-4">Мои отзывы</h2>

                @forelse($user->reviews as $review)
                    <div class="border-b border-white/10 py-3">
                        <p class="text-white">
                            {{ $review->product->name }}
                        </p>
                        <p class="text-gray-400 text-sm">
                            {{ $review->comment }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400">Нет отзывов</p>
                @endforelse
            </div>

        </div>

    </div>
</div>
@endsection
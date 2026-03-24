@extends('layouts.app')

@section('title', 'О нас')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-12">
        <h1 class="text-5xl font-light text-white mb-4">О нас</h1>
        <p class="text-gray-400 text-lg">История и философия La Vie De Vin</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
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
            <div class="grid grid-cols-2 gap-6 mt-8">
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-[#b91c1c]">5+</div>
                    <div class="text-gray-400 text-sm">лет на рынке</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-[#b91c1c]">500+</div>
                    <div class="text-gray-400 text-sm">довольных клиентов</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-[#b91c1c]">50+</div>
                    <div class="text-gray-400 text-sm">виноделен-партнеров</div>
                </div>
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-[#b91c1c]">20+</div>
                    <div class="text-gray-400 text-sm">стран-производителей</div>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <img src="https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=1887" 
                 alt="Виноградники"
                 class="rounded-lg shadow-2xl border border-white/10">
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#b91c1c]/10 rounded-full blur-3xl"></div>
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#b91c1c]/5 rounded-full blur-2xl"></div>
        </div>
    </div>

    <div class="mt-20 text-center">
        <h2 class="text-3xl font-light text-white mb-6">Наши ценности</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6">
                <div class="w-12 h-12 bg-[#b91c1c]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Качество</h3>
                <p class="text-gray-400 text-sm">Только лучшие образцы от проверенных производителей</p>
            </div>
            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6">
                <div class="w-12 h-12 bg-[#b91c1c]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Экспертность</h3>
                <p class="text-gray-400 text-sm">Наши сомелье лично отбирают каждую позицию</p>
            </div>
            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6">
                <div class="w-12 h-12 bg-[#b91c1c]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Забота</h3>
                <p class="text-gray-400 text-sm">Индивидуальный подход к каждому клиенту</p>
            </div>
        </div>
    </div>
</div>
@endsection
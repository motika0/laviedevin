@extends('layouts.app')

@section('title', 'Сертификаты')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-12">
        <h1 class="text-5xl font-light text-white mb-4">Сертификаты</h1>
        <p class="text-gray-400 text-lg">Подарочные сертификаты La Vie De Vin</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <div>
            <h2 class="text-3xl font-light text-white mb-4">Подарите <span class="font-bold text-[#b91c1c]">вкус</span></h2>
            <p class="text-gray-300 mb-6 leading-relaxed">
                Подарочный сертификат La Vie De Vin — это идеальный подарок для ценителей 
                хорошего алкоголя. Получатель сможет самостоятельно выбрать напиток по своему вкусу.
            </p>
            <ul class="space-y-3 mb-8">
                <li class="flex items-center gap-3 text-gray-400">
                    <svg class="w-5 h-5 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Срок действия — 12 месяцев
                </li>
                <li class="flex items-center gap-3 text-gray-400">
                    <svg class="w-5 h-5 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Действует на любой товар в магазине
                </li>
                <li class="flex items-center gap-3 text-gray-400">
                    <svg class="w-5 h-5 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Можно использовать онлайн и в магазине
                </li>
                <li class="flex items-center gap-3 text-gray-400">
                    <svg class="w-5 h-5 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Бесплатная доставка сертификата
                </li>
            </ul>
            
            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6">
                <h3 class="text-white font-semibold mb-4">Доступные номиналы</h3>
                <div class="flex flex-wrap gap-3 mb-6">
                    <span class="px-4 py-2 bg-[#b91c1c] text-white rounded-lg">100 BYN</span>
                    <span class="px-4 py-2 bg-[#b91c1c] text-white rounded-lg">200 BYN</span>
                    <span class="px-4 py-2 bg-[#b91c1c] text-white rounded-lg">300 BYN</span>
                    <span class="px-4 py-2 bg-[#b91c1c] text-white rounded-lg">500 BYN</span>
                    <span class="px-4 py-2 bg-[#b91c1c] text-white rounded-lg">1000 BYN</span>
                </div>
                <p class="text-gray-500 text-sm text-center">
                    Для приобретения сертификата свяжитесь с нами по телефону или в магазине
                </p>
            </div>
        </div>
        
        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-8">
            <h3 class="text-2xl font-light text-white mb-6 text-center">Наш магазин</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-[#b91c1c] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="text-white font-medium">Адрес</p>
                        <p class="text-gray-400">г. Минск, ул. Свердлова, 13</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-[#b91c1c] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-white font-medium">Телефон</p>
                        <p class="text-gray-400">+375 (33) 659-72-34</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-[#b91c1c] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-white font-medium">Время работы</p>
                        <p class="text-gray-400">Пн-Вс: 10:00 - 21:00</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-[#b91c1c] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-white font-medium">Email</p>
                        <p class="text-gray-400">info@laviedevin.by</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-gray-500 text-sm">Приходите в наш магазин — мы всегда рады помочь с выбором!</p>
            </div>
        </div>
    </div>
    
</div>
@endsection
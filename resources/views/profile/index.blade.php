@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-light text-white mb-8">Личный кабинет</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Мои данные</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">ФИО</p>
                        <p class="text-white">{{ Auth::user()->getFullName() }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm">Email</p>
                        <p class="text-white">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm">Телефон</p>
                        <p class="text-white">{{ Auth::user()->phone }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm">Дата рождения</p>
                        <p class="text-white">{{ Auth::user()->birth_date->format('d.m.Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm">Бонусы</p>
                        <p class="text-[#b91c1c] font-bold">{{ Auth::user()->getBonusPoints() }} баллов</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Подтверждение возраста</h2>
                
                @if(Auth::user()->ageVerification && Auth::user()->ageVerification->isVerified())
                    <div class="bg-green-900/20 border border-green-900 rounded-lg p-4 mb-4">
                        <p class="text-green-400">✅ Возраст подтвержден</p>
                        <p class="text-gray-400 text-sm mt-1">
                            Подтверждено: {{ Auth::user()->ageVerification->verified_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                @else
                    <div class="bg-yellow-900/20 border border-yellow-900 rounded-lg p-4 mb-4">
                        <p class="text-yellow-400">⚠️ Возраст не подтвержден</p>
                        <p class="text-gray-400 text-sm mt-1">
                            Для покупки алкоголя необходимо подтвердить возраст
                        </p>
                    </div>
                    
                    <form id="age-verification-form" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Загрузите фото паспорта</label>
                            <input type="file" 
                                   id="passport-file"
                                   accept="image/*"
                                   class="w-full bg-[#111111] border border-white/10 rounded-lg px-4 py-3 text-white">
                        </div>
                        
                        <button type="button" 
                                onclick="verifyAge()"
                                class="w-full bg-[#b91c1c] text-white px-6 py-3 rounded-lg hover:bg-[#991b1b] transition">
                            Отправить на проверку
                        </button>
                    </form>
                    
                    <div id="verification-status" class="mt-4 hidden">
                        <div class="flex items-center gap-3 text-gray-400">
                            <svg class="animate-spin h-5 w-5 text-[#b91c1c]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Идет проверка...</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <div class="bg-[#1a1a1a] border border-white/5 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-white mb-6">История заказов</h2>
                
                @php
                    $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->get();
                @endphp
                
                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-400 mb-4">У вас пока нет заказов</p>
                        <a href="{{ route('products.index') }}" 
                           class="text-[#b91c1c] hover:text-white transition">
                            Перейти в каталог
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="bg-[#111111] border border-white/5 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="text-white font-semibold">Заказ №{{ $order->order_number }}</span>
                                        <span class="text-gray-400 text-sm ml-3">
                                            {{ $order->created_at->format('d.m.Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 bg-{{ $order->status === 'выполнен' ? 'green' : ($order->status === 'отменен' ? 'red' : 'yellow') }}-900/20 border border-{{ $order->status === 'выполнен' ? 'green' : ($order->status === 'отменен' ? 'red' : 'yellow') }}-900 text-{{ $order->status === 'выполнен' ? 'green' : ($order->status === 'отменен' ? 'red' : 'yellow') }}-400 text-xs rounded-full">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-400 text-sm">
                                            Товаров: {{ $order->items->sum('quantity') }} шт
                                        </p>
                                        <p class="text-white font-bold mt-1">
                                            {{ number_format($order->final_amount, 0, '', ' ') }} ₽
                                        </p>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="border border-white/10 text-white px-4 py-2 rounded-lg hover:border-[#b91c1c] hover:text-[#b91c1c] transition">
                                            Подробнее
                                        </a>
                                        
                                        @if($order->status === 'новый')
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="border border-red-900/50 text-red-400 px-4 py-2 rounded-lg hover:bg-red-900/20 transition"
                                                        onclick="return confirm('Отменить заказ?')">
                                                    Отменить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyAge() {
    const fileInput = document.getElementById('passport-file');
    const form = document.getElementById('age-verification-form');
    const statusDiv = document.getElementById('verification-status');
    
    if (!fileInput.files.length) {
        alert('Выберите файл');
        return;
    }
    
    // Показываем статус загрузки
    form.classList.add('hidden');
    statusDiv.classList.remove('hidden');
    
    // Имитация загрузки и проверки (5 секунд)
    setTimeout(() => {
        // Отправляем запрос на сервер
        fetch('/profile/verify-age', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                verified: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Ошибка проверки');
                form.classList.remove('hidden');
                statusDiv.classList.add('hidden');
            }
        });
    }, 5000);
}
</script>
@endpush
@endsection
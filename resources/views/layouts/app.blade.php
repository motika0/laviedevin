@auth
    <div class="flex items-center space-x-4">
        <span class="text-gray-700">{{ Auth::user()->getFullName() }}</span>
        <span class="text-sm text-gray-500">({{ Auth::user()->getBonusPoints() }} бонусов)</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800">Выйти</button>
        </form>
    </div>
@else
    <div class="flex items-center space-x-4">
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Вход</a>
        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Регистрация</a>
    </div>
@endauth
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - La Vie De Vin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #111111;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .auth-wrapper {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .left-side {
            background: #151515;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .input-clean {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            color: #e5e5e5;
            padding: 0.75rem 0;
            width: 100%;
        }

        .input-clean:focus {
            outline: none;
            border-color: #b91c1c;
        }

        .btn-main {
            background: #b91c1c;
            color: white;
            padding: 0.9rem;
            border-radius: 0.375rem;
            font-weight: 500;
            width: 100%;
        }

        .btn-main:hover {
            background: #991b1b;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

<div class="auth-wrapper w-full max-w-5xl rounded-xl overflow-hidden grid md:grid-cols-2">

    <!-- Левая часть -->
    <div class="left-side hidden md:flex flex-col justify-between p-12">
        <div>
            <a href="{{ route('home') }}" class="text-2xl font-light tracking-widest text-white">
                LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
            </a>

            <div class="mt-16">
                <h1 class="text-3xl font-light text-white leading-snug">
                    Вступите в клуб.<br>
                    Начните свой путь.
                </h1>
                <p class="text-gray-500 mt-4 text-sm">
                    Доступ к персональным предложениям и коллекциям.
                </p>
            </div>
        </div>

        <div class="text-gray-600 text-xs">
            © {{ date('Y') }} La Vie De Vin
        </div>
    </div>

    <!-- Правая часть -->
    <div class="p-10 md:p-14 bg-[#181818]">

        <h2 class="text-2xl font-light text-white mb-2">
            Создание аккаунта
        </h2>
        <p class="text-gray-500 text-sm mb-10">
            Заполните данные для регистрации
        </p>

        @if ($errors->any())
            <div class="bg-red-900/20 border border-red-900 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-8">
            @csrf

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                        Фамилия
                    </label>
                    <input type="text" name="surname" value="{{ old('surname') }}" required class="input-clean">
                </div>

                <div>
                    <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                        Имя
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-clean">
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Отчество
                </label>
                <input type="text" name="patronymic" value="{{ old('patronymic') }}" class="input-clean">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input-clean">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Телефон
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="input-clean" placeholder="+375 (29) 123-45-67">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Дата рождения
                </label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="input-clean">
                <p class="text-xs text-gray-600 mt-2">
                    Только для совершеннолетних
                </p>
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Пароль
                </label>
                <input type="password" name="password" required class="input-clean">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Подтверждение пароля
                </label>
                <input type="password" name="password_confirmation" required class="input-clean">
            </div>

            <button type="submit" class="btn-main">
                Зарегистрироваться
            </button>

            <p class="text-sm text-gray-500">
                Уже есть аккаунт?
                <a href="{{ route('login') }}" class="text-[#b91c1c]">
                    Войти
                </a>
            </p>
        </form>

    </div>

</div>

</body>
</html>
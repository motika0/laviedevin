<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - La Vie De Vin</title>
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
            background: linear-gradient(
                160deg,
                #1a1a1a 0%,
                #141414 60%,
                #111111 100%
            );
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
            padding: 0.85rem;
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

<div class="auth-wrapper w-full max-w-4xl rounded-xl overflow-hidden grid md:grid-cols-2">

    <!-- Левая часть -->
    <div class="left-side hidden md:flex flex-col justify-between p-12">
        <div>
            <a href="{{ route('home') }}" class="text-2xl font-light tracking-widest text-white">
                LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
            </a>

            <div class="mt-16">
                <h1 class="text-3xl font-light text-white leading-snug">
                    Эстетика вина.<br>
                    Простота входа.
                </h1>
                <p class="text-gray-500 mt-4 text-sm">
                    Доступ к вашему персональному пространству.
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
            Вход в аккаунт
        </h2>
        <p class="text-gray-500 text-sm mb-10">
            Пожалуйста, введите данные для входа
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

        <form method="POST" action="{{ route('login.store') }}" class="space-y-8">
            @csrf

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="input-clean"
                       placeholder="your@email.com">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-wider mb-2">
                    Пароль
                </label>
                <input type="password"
                       name="password"
                       required
                       class="input-clean"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-400">
                    <input type="checkbox"
                           name="remember"
                           class="mr-2 accent-[#b91c1c]">
                    Запомнить меня
                </label>
            </div>

            <button type="submit" class="btn-main">
                Войти
            </button>

            <p class="text-sm text-gray-500">
                Нет аккаунта?
                <a href="{{ route('register') }}"
                   class="text-[#b91c1c]">
                    Зарегистрироваться
                </a>
            </p>
        </form>
    </div>

</div>

</body>
</html>
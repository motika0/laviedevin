<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - La Vie De Vin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1a1a1a;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .premium-card {
            background: #242424;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .input-premium {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e5e5e5;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            width: 100%;
        }
        .input-premium:focus {
            outline: none;
            border-color: #b91c1c;
            box-shadow: 0 0 0 2px rgba(185, 28, 28, 0.2);
        }
        .btn-primary {
            background: #b91c1c;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(185, 28, 28, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="premium-card rounded-lg p-8 w-full max-w-md">
        <!-- Логотип -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-2xl font-light tracking-wider text-white">
                LA VIE <span class="font-bold text-[#b91c1c]">DE VIN</span>
            </a>
        </div>
        
        <h2 class="text-2xl font-light text-white mb-2 text-center">Добро пожаловать</h2>
        <p class="text-gray-400 text-center mb-8">Войдите в свой аккаунт</p>
        
        @if ($errors->any())
            <div class="bg-red-900/20 border border-red-900 text-red-400 px-4 py-3 rounded-lg mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       class="input-premium"
                       placeholder="your@email.com">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-300 text-sm font-medium mb-2">Пароль</label>
                <input type="password" 
                       name="password" 
                       required
                       class="input-premium"
                       placeholder="••••••••">
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-600 bg-[#1a1a1a] text-[#b91c1c] focus:ring-[#b91c1c]">
                    <span class="ml-2 text-sm text-gray-400">Запомнить меня</span>
                </label>
                
             
            </div>
            
            <button type="submit" class="btn-primary mb-4">
                Войти
            </button>
            
            <p class="text-center text-gray-400">
                Нет аккаунта? 
                <a href="{{ route('register') }}" class="text-[#b91c1c] hover:text-white transition">
                    Зарегистрироваться
                </a>
            </p>
        </form>
    </div>
</body>
</html>
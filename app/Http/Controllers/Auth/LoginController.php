<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Показать форму входа
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Обработать вход
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Проверка возраста
            if (!Auth::user()->isAdult()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Доступ только для совершеннолетних',
                ]);
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ]);
    }
}
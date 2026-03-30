<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loyalty;
use App\Models\AgeVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date|before:today',
        ]);

        $birthDate = \Carbon\Carbon::parse($request->birth_date);
        $age = $birthDate->age;
        
        if ($age < 18) {
            return back()->withErrors([
                'birth_date' => 'Вам должно быть 18 лет для регистрации',
            ])->withInput();
        }

        $user = User::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'is_verified' => true,
        ]);

        AgeVerification::create([
            'user_id' => $user->id,
            'verified_at' => now(),
        ]);

        Loyalty::create([
            'user_id' => $user->id,
            'points' => 100,
            'total_earned' => 100,
            'level' => 'бронза',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Регистрация успешна!');
    }
}
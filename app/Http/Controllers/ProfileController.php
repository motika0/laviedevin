<?php

namespace App\Http\Controllers;

use App\Models\AgeVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
public function index()
{
    $user = Auth::user();

    $user->load([
        'favorites.product',
        'reviews.product',
        'orders.items.product',
        'ageVerification',
    ]);

    return view('profile.index', compact('user'));
}

public function verifyAge(Request $request)
{
    $request->validate([
        'document' => 'required|file|max:5120'
    ]);

    $user = Auth::user();

    AgeVerification::updateOrCreate(
        ['user_id' => $user->id],
        ['verified_at' => now()]
    );

    return back()->with('success', 'Возраст подтвержден');
}
}
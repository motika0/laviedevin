<?php

namespace App\Http\Controllers;

use App\Models\AgeVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }
    
    public function verifyAge(Request $request)
    {
        $user = Auth::user();
        
        AgeVerification::updateOrCreate(
            ['user_id' => $user->id],
            ['verified_at' => now()]
        );
        
        return response()->json(['success' => true]);
    }
}
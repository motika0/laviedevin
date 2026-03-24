<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем новинки для главной страницы
        $newProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        return view('home', compact('newProducts'));
    }
}
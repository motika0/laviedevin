<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product')->get();
        
        return view('favorites.index', compact('favorites'));
    }
    

    public function toggle(Product $product)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
        
        if ($favorite) {
            $favorite->delete();
            $message = 'Товар удален из избранного';
            $isFavorite = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $message = 'Товар добавлен в избранное';
            $isFavorite = true;
        }
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite,
                'message' => $message,
                'count' => $user->favorites()->count()
            ]);
        }
        
        return back()->with('success', $message);
    }
    

    public function check(Product $product)
    {
        $isFavorite = Auth::user()->hasInFavorites($product->id);
        
        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
    

    public function count()
    {
        $count = Auth::user()->favorites()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
    
    public function clear()
    {
        Auth::user()->favorites()->delete();
        
        return back()->with('success', 'Избранное очищено');
    }
}
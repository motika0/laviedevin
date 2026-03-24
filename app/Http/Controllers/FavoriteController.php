<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Показать список избранного
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product')->get();
        
        return view('favorites.index', compact('favorites'));
    }
    
    /**
     * Добавить/удалить из избранного (переключатель)
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
        
        if ($favorite) {
            // Удаляем из избранного
            $favorite->delete();
            $message = 'Товар удален из избранного';
            $isFavorite = false;
        } else {
            // Добавляем в избранное
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $message = 'Товар добавлен в избранное';
            $isFavorite = true;
        }
        
        // Если это AJAX запрос
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite,
                'message' => $message,
                'count' => $user->favorites()->count()
            ]);
        }
        
        // Обычный редирект
        return back()->with('success', $message);
    }
    
    /**
     * Проверить, есть ли товар в избранном
     */
    public function check(Product $product)
    {
        $isFavorite = Auth::user()->hasInFavorites($product->id);
        
        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
    
    /**
     * Получить количество избранных товаров
     */
    public function count()
    {
        $count = Auth::user()->favorites()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
    
    /**
     * Очистить всё избранное
     */
    public function clear()
    {
        Auth::user()->favorites()->delete();
        
        return back()->with('success', 'Избранное очищено');
    }
}
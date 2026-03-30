<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function create()
    {
        $purchasedProducts = Auth::user()->orders()
            ->where('status', 'выполнен')
            ->with('items.product')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('product')
            ->unique('id');
        
        return view('partials.review-form', compact('purchasedProducts'));
    }

   public function store(Request $request, $productId)
{
    try {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        $hasPurchased = Auth::user()->orders()
            ->where('status', 'выполнен')
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'Вы можете оставить отзыв только на купленные товары'
            ], 403);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => (int) $request->rating,
            'comment' => trim($request->comment),
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Спасибо за отзыв!',
            'review' => $review
        ]);

    } catch (\Exception $e) {
        Log::error('Review creation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Произошла ошибка при сохранении отзыва: ' . $e->getMessage()
        ], 500);
    }
}

    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Отзыв обновлен'
        ]);
    }

    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Отзыв удален'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        // Проверка, покупал ли пользователь этот товар
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->where('status', 'выполнен')
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Вы можете оставить отзыв только на купленные товары');
        }

        // Проверка, не оставлял ли уже отзыв
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв на этот товар');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // На модерацию
        ]);

        return back()->with('success', 'Отзыв отправлен на модерацию');
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
            'is_approved' => false, // Снова на модерацию
        ]);

        return back()->with('success', 'Отзыв обновлен');
    }

    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $review->delete();

        return back()->with('success', 'Отзыв удален');
    }
}
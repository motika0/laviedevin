<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
public function index()
{
    $cartItems = Auth::user()->cart()->with('product')->get();
    $total = Auth::user()->getCartTotal(); 
    $bonusPoints = Auth::user()->getBonusPoints();
    $maxBonusDiscount = Auth::user()->loyalty ? Auth::user()->loyalty->getMaxDiscount() : 0;

    return view('cart.index', compact('cartItems', 'total', 'bonusPoints', 'maxBonusDiscount'));
}
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);


        if (!$product->hasEnoughStock($request->quantity)) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        if (!Auth::user()->isAdult()) {
            return back()->with('error', 'Только для совершеннолетних');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increaseQuantity($request->quantity);
            $message = 'Количество товара обновлено';
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
            $message = 'Товар добавлен в корзину';
        }

        return redirect()->route('cart.index')->with('success', $message);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);

        if (!$cartItem->product->hasEnoughStock($request->quantity)) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Количество обновлено');
    }

    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        Auth::user()->clearCart();
        return back()->with('success', 'Корзина очищена');
    }


    public function applyBonus(Request $request)
    {
        $request->validate([
            'bonus_points' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $cartTotal = $user->getCartTotal();
        $bonusPoints = $user->getBonusPoints();
        $requestedBonus = $request->bonus_points;

        if ($requestedBonus > $bonusPoints) {
            return back()->with('error', 'Недостаточно бонусов');
        }

        $conversionRate = $user->loyalty ? $user->loyalty->getConversionRate() : 0.5;
        $maxDiscount = $cartTotal;
        $bonusDiscount = min($requestedBonus * $conversionRate, $maxDiscount);

        session(['bonus_discount' => $bonusDiscount]);
        session(['bonus_used' => $requestedBonus]);

        return back()->with('success', 'Бонусы применены');
    }

    public function removeBonus()
    {
        session()->forget(['bonus_discount', 'bonus_used']);
        return back()->with('success', 'Бонусы убраны');
    }
}
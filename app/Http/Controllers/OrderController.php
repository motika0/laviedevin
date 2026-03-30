<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cartItems = Auth::user()->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $total = Auth::user()->getCartTotal();
        $bonusPoints = Auth::user()->getBonusPoints();
        $bonusDiscount = session('bonus_discount', 0);
        $finalTotal = $total - $bonusDiscount;

        return view('orders.checkout', compact('cartItems', 'total', 'bonusPoints', 'bonusDiscount', 'finalTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10',
            'phone' => 'required|string',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина пуста'
            ], 400);
        }

        foreach ($cartItems as $item) {
            if (!$item->product->hasEnoughStock($item->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "Товар {$item->product->name} недоступен в нужном количестве"
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            $totalAmount = $user->getCartTotal();
            $bonusDiscount = session('bonus_discount', 0);
            $bonusUsed = session('bonus_used', 0);
            $finalAmount = $totalAmount - $bonusDiscount;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'discount_amount' => $bonusDiscount,
                'final_amount' => $finalAmount,
                'bonus_used' => $bonusUsed,
                'status' => 'новый',
                'payment_status' => 'ожидает',
                'address' => $request->address,
                'phone' => $request->phone,
                'comment' => $request->comment,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->getCurrentPrice(),
                ]);

                $item->product->decreaseStock($item->quantity);
            }

            if ($bonusUsed > 0) {
                $loyalty = $user->loyalty;
                if ($loyalty) {
                    $loyalty->spendPoints($bonusUsed, 'Оплата заказа #' . $order->order_number, $order);
                }
            }

            $user->clearCart();
            session()->forget(['bonus_discount', 'bonus_used']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно оформлен',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при оформлении заказа: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['новый', 'в обработке'])) {
            return back()->with('error', 'Этот заказ нельзя отменить');
        }

        DB::beginTransaction();

        try {
            foreach ($order->items as $item) {
                $item->product->increaseStock($item->quantity);
            }

            if ($order->bonus_used > 0) {
                $loyalty = $order->user->loyalty;
                if ($loyalty) {
                    $loyalty->addPoints($order->bonus_used, 'Возврат бонусов за отмену заказа #' . $order->order_number, $order);
                }
            }

            $order->status = 'отменен';
            $order->save();

            DB::commit();

            return back()->with('success', 'Заказ отменен');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при отмене заказа');
        }
    }

    public function reorder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        foreach ($order->items as $item) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $item->product_id)
                ->first();

            if ($cartItem) {
                $cartItem->increaseQuantity($item->quantity);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Товары добавлены в корзину');
    }

    public function pay($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->payment_status === 'оплачен') {
            return back()->with('error', 'Заказ уже оплачен');
        }

        $order->payment_status = 'оплачен';
        $order->save();

        return back()->with('success', 'Заказ оплачен');
    }

    public function processOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        if ($order->status === 'новый') {
            $order->status = 'выполнен';
            $order->payment_status = 'оплачен';
            $order->save();

            $earnedBonus = floor($order->final_amount * 0.1);

            if ($earnedBonus > 0) {
                $user = $order->user;
                $loyalty = $user->loyalty ?? Loyalty::create(['user_id' => $user->id]);
                $loyalty->addPoints($earnedBonus, 'Начисление 10% за заказ #' . $order->order_number, $order);
            }
        }

        return response()->json(['success' => true]);
    }
}

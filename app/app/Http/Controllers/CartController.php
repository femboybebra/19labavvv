<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with('product')->where('user_id', Auth::id())->get();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);
        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $item = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'quantity'   => 1,
            ]);
        }

        return back()->with('message', 'Товар добавлен в корзину.');
    }

    public function remove(Request $request)
    {
        $request->validate(['item_id' => 'required|exists:cart_items,id']);

        CartItem::where('id', $request->item_id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('message', 'Товар удалён из корзины.');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        CartItem::where('id', $request->item_id)
            ->where('user_id', Auth::id())
            ->update(['quantity' => $request->quantity]);

        return back();
    }

    public function order(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Неверный пароль.']);
        }

        $items = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($items->isEmpty()) {
            return back()->withErrors(['cart' => 'Корзина пуста.']);
        }

        // Check stock availability
        foreach ($items as $item) {
            if ($item->product->count < $item->quantity) {
                $order = Order::create([
                    'user_id'       => $user->id,
                    'status'        => 'cancelled',
                    'total_price'   => $items->sum(fn($i) => $i->product->price * $i->quantity),
                    'cancel_reason' => 'Количество товаров превышает имеющееся на складе',
                ]);

                foreach ($items as $i) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $i->product_id,
                        'quantity'   => $i->quantity,
                        'price'      => $i->product->price,
                    ]);
                }

                CartItem::where('user_id', $user->id)->delete();

                return redirect('/orders')->with('message', 'Заказ отменён: недостаточно товара на складе.');
            }
        }

        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);

        $order = Order::create([
            'user_id'     => $user->id,
            'status'      => 'new',
            'total_price' => $total,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
            $item->product->decrement('count', $item->quantity);
        }

        CartItem::where('user_id', $user->id)->delete();

        return redirect('/orders')->with('message', 'Заказ успешно оформлен.');
    }
}

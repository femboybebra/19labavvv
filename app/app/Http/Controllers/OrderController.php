<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')->where('user_id', Auth::id());

        if ($request->filled('status') && in_array($request->status, ['new', 'confirmed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        return view('orders', compact('orders'));
    }

    public function destroy(Request $request)
    {
        Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('message', 'Заказ удалён.');
    }
}

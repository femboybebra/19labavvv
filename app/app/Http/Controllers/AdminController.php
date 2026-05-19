<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                abort(403, 'Доступ запрещён.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $categories = Category::all();

        $ordersQuery = Order::with('items.product', 'user');

        if ($request->filled('status') && in_array($request->status, ['new', 'confirmed', 'cancelled'])) {
            $ordersQuery->where('status', $request->status);
        }

        $orders = $ordersQuery->latest()->get();

        return view('admin.index', compact('categories', 'orders'));
    }

    public function addCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $request->name]);
        return back()->with('message', 'Категория добавлена.');
    }

    public function deleteCategory(Request $request)
    {
        $request->validate(['category_id' => 'required|exists:categories,id']);
        Category::destroy($request->category_id);
        return back()->with('message', 'Категория удалена.');
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|integer|min:0',
            'country'     => 'required|string|max:255',
            'year'        => 'required|integer|min:1900|max:2100',
            'model'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'count'       => 'required|integer|min:0',
            'image'       => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'country'     => $request->country,
            'year'        => $request->year,
            'model'       => $request->model,
            'category_id' => $request->category_id,
            'count'       => $request->count,
            'image'       => $imagePath,
        ]);

        return back()->with('message', 'Товар добавлен.');
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:new,confirmed,cancelled',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->update([
            'status'        => $request->status,
            'cancel_reason' => $request->status === 'cancelled' ? ($request->cancel_reason ?? 'Отменено администратором') : null,
        ]);

        return back()->with('message', 'Статус заказа обновлён.');
    }

    public function deleteOrder(Request $request)
    {
        Order::destroy($request->order_id);
        return back()->with('message', 'Заказ удалён.');
    }
}

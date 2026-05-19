<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function catalog(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', '');
        $dir  = $request->get('dir', 'asc');

        if (in_array($sort, ['name', 'price', 'year'])) {
            $query->orderBy($sort, $dir === 'desc' ? 'desc' : 'asc');
        }

        $products   = $query->get();
        $categories = Category::all();

        return view('catalog', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('product', compact('product'));
    }

    public function editForm(Product $product)
    {
        $this->requireAdmin();
        $categories = Category::all();
        return view('product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->requireAdmin();

        $request->validate([
            'name'    => 'required|string|max:255',
            'price'   => 'required|integer|min:0',
            'country' => 'required|string|max:255',
            'year'    => 'required|integer|min:1900|max:2100',
            'model'   => 'required|string|max:255',
            'count'   => 'required|integer|min:0',
            'image'   => 'nullable|image',
        ]);

        $data = $request->only('name', 'price', 'country', 'year', 'model', 'count', 'category_id');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/admin')->with('message', 'Товар обновлён.');
    }

    public function destroy(Request $request)
    {
        $this->requireAdmin();

        $product = Product::findOrFail($request->product_id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('message', 'Товар удалён.');
    }

    private function requireAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
    }
}

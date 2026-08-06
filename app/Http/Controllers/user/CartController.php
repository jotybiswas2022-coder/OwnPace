<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $key => $item) {
            $product = Product::with('primaryImage')->find($item['id'] ?? $key);
            if ($product) {
                $image = $product->thumbnail && !preg_match('/^https?:\/\//i', $product->thumbnail)
                    && !str_starts_with($product->thumbnail, 'C:\\')
                    && !str_starts_with($product->thumbnail, '/tmp/')
                    ? $product->thumbnail
                    : ($product->primaryImage?->image_path ?: $product->images()->first()?->image_path);
                $cart[$key]['thumbnail'] = $image;
                $cart[$key]['name'] = $product->name;
                $cart[$key]['slug'] = $product->slug;
            }
            $total += $item['price'] * $item['quantity'];
        }
        session()->put('cart', $cart);
        return view('frontend.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        if (!$request->quantity) {
            $request->merge(['quantity' => 1]);
        }

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $image = null;
        if ($product->thumbnail && !preg_match('/^https?:\/\//i', $product->thumbnail) && !str_starts_with($product->thumbnail, 'C:\\') && !str_starts_with($product->thumbnail, '/tmp/')) {
            $image = $product->thumbnail;
        }
        if (!$image) {
            $image = $product->primaryImage?->image_path ?: $product->images()->first()?->image_path;
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'thumbnail' => $image,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'count' => count($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'message' => 'Cart updated!']);
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared.');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}

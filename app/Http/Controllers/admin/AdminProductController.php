<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'primaryImage'])->latest()->get();
        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        return view('backend.products.create', compact('categories', 'brands', 'suppliers'));
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('manage', Product::class);

        $slug = Str::slug($request->name);
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $counter++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'base_price' => $request->base_price,
            'shipping_fee' => $request->shipping_fee ?? 0,
            'insurance_fee' => $request->insurance_fee ?? 0,
            'interest_rate' => $request->interest_rate ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status ?? 'active',
            'featured' => $request->featured === '1',
            'specifications' => $request->specifications,
        ]);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $product->load('images');
        return view('backend.products.edit', compact('product', 'categories', 'brands', 'suppliers'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('manage', $product);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $data = $request->validated();
        $data['featured'] = $request->featured === '1';
        $product->update($data);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$product->images()->where('is_primary', true)->exists() && $index === 0,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('manage', $product);

        // Delete images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $this->authorize('manage', $image->product);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }
}

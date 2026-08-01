<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(20);
        return view('backend.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('backend.brands.create');
    }

    public function store(BrandRequest $request)
    {
        $this->authorize('manage', Brand::class);

        Brand::create($request->validated());
        return redirect()->route('admin.brands.index')->with('success', 'Brand added successfully!');
    }

    public function edit(Brand $brand)
    {
        return view('backend.brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $this->authorize('manage', $brand);

        $brand->update($request->validated());
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated!');
    }

    public function destroy(Brand $brand)
    {
        $this->authorize('manage', $brand);

        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Cannot delete brand with existing products.');
        }
        $brand->delete();
        return back()->with('success', 'Brand deleted!');
    }
}

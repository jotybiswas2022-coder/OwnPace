<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierRequest;
use App\Models\Supplier;

class AdminSupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->latest()->paginate(20);
        return view('backend.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('backend.suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $this->authorize('manage', Supplier::class);

        Supplier::create($request->validated());
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('backend.suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('manage', $supplier);

        $supplier->update($request->validated());
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated!');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('manage', $supplier);

        if ($supplier->products()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with existing products.');
        }
        $supplier->delete();
        return back()->with('success', 'Supplier deleted!');
    }
}

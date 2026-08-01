<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromoCodeRequest;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::latest()->paginate(20);
        return view('backend.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        return view('backend.promo-codes.create');
    }

    public function store(PromoCodeRequest $request)
    {
        $this->authorize('manage', PromoCode::class);

        PromoCode::create($request->validated());
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code created!');
    }

    public function edit(PromoCode $promoCode)
    {
        return view('backend.promo-codes.edit', compact('promoCode'));
    }

    public function update(PromoCodeRequest $request, PromoCode $promoCode)
    {
        $this->authorize('manage', $promoCode);

        $promoCode->update($request->validated());
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code updated!');
    }

    public function destroy(PromoCode $promoCode)
    {
        $this->authorize('manage', $promoCode);

        $promoCode->delete();
        return back()->with('success', 'Promo code deleted!');
    }
}

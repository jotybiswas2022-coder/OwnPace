<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TermsRequest;
use App\Models\TermsAndCondition;
use App\Models\InstallmentPlan;

class AdminTermsController extends Controller
{
    public function index()
    {
        $terms = TermsAndCondition::with('installmentPlan')->orderBy('type')->get();
        $plans = InstallmentPlan::orderBy('type')->orderBy('duration')->get();

        return view('backend.terms.index', compact('terms', 'plans'));
    }

    public function store(TermsRequest $request)
    {
        $this->authorize('manage', TermsAndCondition::class);

        TermsAndCondition::create([
            'title' => $request->title,
            'type' => $request->type ?? 'general',
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
            'installment_plan_id' => $request->installment_plan_id ?: null,
        ]);

        return redirect()->route('admin.terms.index')->with('success', 'Terms created successfully!');
    }

    public function update(TermsRequest $request, TermsAndCondition $term)
    {
        $this->authorize('manage', $term);

        $term->update([
            'title' => $request->title,
            'type' => $request->type ?? $term->type,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
            'installment_plan_id' => $request->installment_plan_id ?: null,
        ]);

        return back()->with('success', 'Terms updated successfully!');
    }

    public function destroy(TermsAndCondition $term)
    {
        $this->authorize('manage', $term);

        $term->delete();

        return back()->with('success', 'Terms deleted.');
    }
}

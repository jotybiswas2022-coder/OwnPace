<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstallmentPlanRequest;
use App\Models\InstallmentPlan;

class AdminInstallmentPlanController extends Controller
{
    public function index()
    {
        $this->authorize('manage', InstallmentPlan::class);

        $plans = InstallmentPlan::orderBy('type')->orderBy('duration')->get();

        return view('backend.installment-plans.index', compact('plans'));
    }

    public function store(InstallmentPlanRequest $request)
    {
        $this->authorize('manage', InstallmentPlan::class);

        InstallmentPlan::create([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_days' => $request->duration_days,
            'interest_rate' => $request->interest_rate,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'late_fee_enabled' => $request->boolean('late_fee_enabled'),
            'late_fee_percent' => $request->late_fee_percent ?? 0,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Installment plan created.');
    }

    public function edit(InstallmentPlan $plan)
    {
        $this->authorize('manage', $plan);

        return view('backend.installment-plans.edit', compact('plan'));
    }

    public function update(InstallmentPlanRequest $request, InstallmentPlan $plan)
    {
        $this->authorize('manage', $plan);

        $plan->update([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_days' => $request->duration_days,
            'interest_rate' => $request->interest_rate,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'late_fee_enabled' => $request->boolean('late_fee_enabled'),
            'late_fee_percent' => $request->late_fee_percent ?? 0,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Installment plan updated.');
    }

    public function destroy(InstallmentPlan $plan)
    {
        $this->authorize('manage', $plan);

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Installment plan deleted.');
    }
}

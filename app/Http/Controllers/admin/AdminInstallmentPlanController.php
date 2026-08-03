<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstallmentPlanRequest;
use Illuminate\Http\Request;
use App\Models\InstallmentPlan;

class AdminInstallmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage', InstallmentPlan::class);

        $query = InstallmentPlan::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        if ($request->type && in_array($request->type, ['weekly', 'monthly'])) {
            $query->where('type', $request->type);
        }
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $plans = $query->orderBy('type')->orderBy('duration')->get();

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

    // ===== BULK ACTIONS =====

    public function bulkAction(Request $request)
    {
        $this->authorize('manage', InstallmentPlan::class);

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'plan_ids' => 'required|array|min:1',
            'plan_ids.*' => 'integer|exists:installment_plans,id',
        ]);

        $plans = InstallmentPlan::whereIn('id', $request->plan_ids)->get();

        switch ($request->action) {
            case 'activate':
                $plans->each->update(['is_active' => true]);
                $msg = $plans->count().' plan(s) activated.';
                break;
            case 'deactivate':
                $plans->each->update(['is_active' => false]);
                $msg = $plans->count().' plan(s) deactivated.';
                break;
            default:
                // Orders already on these plans keep their schedule (the
                // plan row is only removed from future checkouts).
                $plans->each->delete();
                $msg = $plans->count().' plan(s) deleted.';
                break;
        }

        return redirect()->route('admin.plans.index')->with('success', $msg);
    }
}

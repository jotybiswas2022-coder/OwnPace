<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestStatusRequest;
use App\Http\Requests\Admin\RejectRequestRequest;
use App\Models\PlanChangeRequest;
use App\Models\ProductRequest;
use App\Models\ExchangeRequest;
use App\Models\AccountDeletionRequest;
use App\Models\InstallmentPlan;

class AdminRequestController extends Controller
{
    public function planChanges()
    {
        $requests = PlanChangeRequest::with(['user', 'order', 'currentPlan', 'requestedPlan'])
            ->latest()
            ->paginate(20);
        return view('backend.requests.plan-changes', compact('requests'));
    }

    public function approvePlanChange(PlanChangeRequest $planChangeRequest)
    {
        $this->authorize('manage', $planChangeRequest);

        $planChangeRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Update the order's installment plan
        $order = $planChangeRequest->order;
        $order->update([
            'installment_plan_id' => $planChangeRequest->requested_plan_id,
        ]);

        // Recalculate installment payments
        $order->installmentPayments()->delete();
        $newPlan = $planChangeRequest->requestedPlan;
        $perInstallment = $order->remaining_amount / $newPlan->duration;
        $dueDate = now();

        for ($i = 1; $i <= $newPlan->duration; $i++) {
            $dueDate = $dueDate->addDays($newPlan->type === 'weekly' ? 7 : 30);
            \App\Models\InstallmentPayment::create([
                'order_id' => $order->id,
                'installment_number' => $i,
                'amount' => $perInstallment,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Plan change approved successfully!');
    }

    public function rejectPlanChange(RejectRequestRequest $request, PlanChangeRequest $planChangeRequest)
    {
        $this->authorize('manage', $planChangeRequest);

        $planChangeRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Plan change rejected.');
    }

    public function productRequests()
    {
        $requests = ProductRequest::with('user')->latest()->paginate(20);
        return view('backend.requests.product-requests', compact('requests'));
    }

    public function updateProductRequest(RequestStatusRequest $request, ProductRequest $productRequest)
    {
        $this->authorize('manage', $productRequest);

        $productRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'approved_at' => $request->status === 'approved' ? now() : null,
        ]);

        return back()->with('success', 'Product request ' . $request->status . '!');
    }

    public function exchangeRequests()
    {
        $requests = ExchangeRequest::with(['user', 'order', 'currentProduct', 'requestedProduct'])
            ->latest()
            ->paginate(20);
        return view('backend.requests.exchange-requests', compact('requests'));
    }

    public function updateExchangeRequest(RequestStatusRequest $request, ExchangeRequest $exchangeRequest)
    {
        $this->authorize('manage', $exchangeRequest);

        $exchangeRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Exchange request ' . $request->status . '!');
    }

    // ===== ACCOUNT CLOSURE =====
    public function deletionRequests()
    {
        $requests = AccountDeletionRequest::with('user')->latest()->paginate(20);
        return view('backend.requests.deletion-requests', compact('requests'));
    }

    public function approveDeletion(AccountDeletionRequest $deletionRequest)
    {
        $this->authorize('manage', $deletionRequest);

        $deletionRequest->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        // Deactivate the account (soft-disable) — the user can still log in
        // but the account is marked inactive until purged by an admin.
        $deletionRequest->user?->update(['is_active' => false]);

        return back()->with('success', 'Account closure approved — user deactivated.');
    }

    public function rejectDeletion(RejectRequestRequest $request, AccountDeletionRequest $deletionRequest)
    {
        $this->authorize('manage', $deletionRequest);

        $deletionRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Account closure request rejected.');
    }
}

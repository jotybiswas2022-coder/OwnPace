<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstallmentPayment;

/**
 * Transaction history — every installment payment across all customers,
 * searchable, filterable and exportable to CSV.
 */
class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage', \App\Models\Order::class);

        $query = InstallmentPayment::with(['order.user', 'order.installmentPlan']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($oq) use ($search) {
                    $oq->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            });
        }

        if ($request->status && in_array($request->status, ['pending', 'paid', 'overdue', 'partial'])) {
            $query->where('status', $request->status);
        }

        if ($request->method) {
            $query->where('payment_method', $request->method);
        }

        if ($request->date_from) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        if ($request->sort === 'amount_asc') {
            $query->orderBy('amount');
        } elseif ($request->sort === 'amount_desc') {
            $query->orderByDesc('amount');
        } elseif ($request->sort === 'oldest') {
            $query->oldest('due_date');
        } else {
            $query->latest('due_date');
        }

        $payments = $query->paginate(20)->withQueryString();

        $summary = [
            'total_collected' => InstallmentPayment::where('status', 'paid')->sum('paid_amount'),
            'pending_due' => InstallmentPayment::where('status', 'pending')->sum('amount'),
            'overdue' => InstallmentPayment::where('status', 'overdue')->count(),
        ];

        return view('backend.transactions.index', compact('payments', 'summary'));
    }

    /**
     * CSV export of the same filtered result set (no pagination limit).
     */
    public function export(Request $request)
    {
        $this->authorize('manage', \App\Models\Order::class);

        $query = InstallmentPayment::with(['order.user', 'order.installmentPlan']);

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->status && in_array($request->status, ['pending', 'paid', 'overdue', 'partial'])) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        $rows = $query->latest('due_date')->get();

        $csv = "Installment #,Order #,Customer,Plan,Amount,Paid Amount,Late Fee,Due Date,Paid Date,Status,Payment Method\n";
        foreach ($rows as $p) {
            $plan = $p->order?->installmentPlan;
            $planLabel = $plan ? ($plan->name.' ('.$plan->duration.'×)') : '—';
            $csv .= implode(',', [
                '"'.$p->installment_number.'"',
                '"'.($p->order?->order_number ?? '').'"',
                '"'.($p->order?->user?->name ?? '').'"',
                '"'.$planLabel.'"',
                number_format((float) $p->amount, 2),
                number_format((float) $p->paid_amount, 2),
                number_format((float) $p->late_fee, 2),
                $p->due_date?->format('Y-m-d') ?? '',
                $p->paid_date?->format('Y-m-d') ?? '',
                $p->status,
                ($p->payment_method ?? ''),
            ])."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="installment_transactions_'.now()->format('Y-m-d').'.csv"',
        ]);
    }
}

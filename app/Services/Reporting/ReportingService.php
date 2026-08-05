<?php

namespace App\Services\Reporting;

use App\Models\InstallmentPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\ProductRequest;
use App\Models\User;

/**
 * ReportingService — pure aggregations behind the Reporting dashboard.
 *
 *   sales()                  revenue + order volume over a rolling window
 *   installmentPerformance() on-time / late / overdue / defaulted split
 *   customerBehavior()       repeat purchase rate, AOV, top products & buyers
 *
 * Revenue is measured from successful payment transactions (type=payment),
 * the same money source the dashboard uses. Order counts exclude cancelled
 * and failed orders.
 */
class ReportingService
{
    public const PERIODS = [7, 30, 90, 365];

    /** Days past due before an overdue installment counts as defaulted. */
    public const DEFAULTED_AFTER_DAYS = 30;

    // ===== SALES OVER TIME =====

    /**
     * Daily revenue + orders for the rolling window.
     *
     * @return array{labels: array<int, string>, revenue: array<int, float>, orders: array<int, int>, revenue_total: float, order_total: int, aov: float, change: float}
     */
    public static function sales(int $days): array
    {
        $from = now()->subDays($days - 1)->startOfDay();

        $revenueByDay = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $ordersByDay = Order::whereNull('deleted_at')
            ->whereNotIn('status', ['cancelled', 'failed'])
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $revenue[] = round((float) ($revenueByDay[$day] ?? 0), 2);
            $orders[] = (int) ($ordersByDay[$day] ?? 0);
        }

        $revenueTotal = round(array_sum($revenue), 2);
        $orderTotal = array_sum($orders);
        $aov = $orderTotal > 0 ? round($revenueTotal / $orderTotal, 2) : 0;

        // Revenue delta vs the previous window of equal length.
        $prevRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->whereBetween('created_at', [
                now()->subDays($days * 2 - 1)->startOfDay(),
                now()->subDays($days)->endOfDay(),
            ])
            ->sum('amount');

        $change = $prevRevenue > 0
            ? round(($revenueTotal - (float) $prevRevenue) / (float) $prevRevenue * 100, 1)
            : ($revenueTotal > 0 ? 100.0 : 0.0);

        return compact('labels', 'revenue', 'orders', 'revenueTotal', 'orderTotal', 'aov', 'change');
    }

    // ===== INSTALLMENT PERFORMANCE =====

    /**
     * On-time / late / overdue / defaulted split (counts + amounts) plus a
     * 12-month trend table.
     *
     * Definitions:
     *   on-time   paid on or before the due date
     *   late      paid after the due date
     *   overdue   currently overdue, less than 30 days past due
     *   defaulted currently overdue, 30+ days past due
     *
     * @return array{total: int, on_time: array, late: array, overdue: array, defaulted: array, months: array<int, array>}
     */
    public static function installmentPerformance(): array
    {
        $paid = InstallmentPayment::where('status', 'paid')->get(['amount', 'paid_date', 'due_date']);
        $overdueRows = InstallmentPayment::where('status', 'overdue')->get(['amount', 'due_date']);

        $bucket = function ($rows, $dueFrom) {
            return $rows->filter(fn ($p) => $p->due_date && $p->due_date->gte($dueFrom));
        };

        $onTime = $paid->filter(fn ($p) => $p->paid_date && $p->due_date && ! $p->paid_date->gt($p->due_date));
        $late = $paid->filter(fn ($p) => $p->paid_date && $p->due_date && $p->paid_date->gt($p->due_date));
        $overdue = $bucket($overdueRows, now()->subDays(self::DEFAULTED_AFTER_DAYS));
        $defaulted = $overdueRows->filter(fn ($p) => $p->due_date && $p->due_date->lt(now()->subDays(self::DEFAULTED_AFTER_DAYS)));

        $summarize = fn ($rows) => [
            'count' => $rows->count(),
            'amount' => round((float) $rows->sum('amount'), 2),
        ];

        $breakdown = [
            'on_time' => $summarize($onTime),
            'late' => $summarize($late),
            'overdue' => $summarize($overdue),
            'defaulted' => $summarize($defaulted),
        ];

        $total = array_sum(array_column($breakdown, 'count'));

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthPaid = InstallmentPayment::where('status', 'paid')
                ->whereBetween('due_date', [$monthStart, $monthEnd])
                ->get(['amount', 'paid_date', 'due_date']);

            $monthOverdue = InstallmentPayment::where('status', 'overdue')
                ->whereBetween('due_date', [$monthStart, $monthEnd])
                ->get(['amount', 'due_date']);

            $months[] = [
                'label' => $monthStart->format('M Y'),
                'due' => $monthPaid->count() + $monthOverdue->count(),
                'on_time' => $monthPaid->filter(fn ($p) => ! $p->paid_date->gt($p->due_date))->count(),
                'late' => $monthPaid->filter(fn ($p) => $p->paid_date->gt($p->due_date))->count(),
                'overdue' => $monthOverdue->filter(fn ($p) => $p->due_date->gte(now()->subDays(self::DEFAULTED_AFTER_DAYS)))->count(),
                'defaulted' => $monthOverdue->filter(fn ($p) => $p->due_date->lt(now()->subDays(self::DEFAULTED_AFTER_DAYS)))->count(),
            ];
        }

        return compact('total', 'breakdown', 'months');
    }

    // ===== CUSTOMER BEHAVIOR =====

    /**
     * Repeat purchase rate, average order value and the top products, buyers
     * and product requests.
     *
     * @return array<string, mixed>
     */
    public static function customerBehavior(): array
    {
        $activeOrders = fn ($q) => $q->whereNull('deleted_at')->whereNotIn('status', ['cancelled', 'failed']);

        $totalCustomers = User::count();

        $buyers = (clone $activeOrders)(Order::query())->distinct()->count('user_id');

        $repeatBuyers = (clone $activeOrders)(Order::query())
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) >= 2')
            ->get()
            ->count();

        $repeatRate = $buyers > 0 ? round($repeatBuyers / $buyers * 100, 1) : 0;

        $revenue = (float) PaymentTransaction::where('status', 'success')->where('type', 'payment')->sum('amount');
        $ordersCount = (clone $activeOrders)(Order::query())->count();
        $aov = $ordersCount > 0 ? round($revenue / $ordersCount, 2) : 0;

        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->whereNotIn('orders.status', ['cancelled', 'failed'])
            ->selectRaw("COALESCE(NULLIF(order_items.product_name, ''), CAST(order_items.product_id AS CHAR)) as label")
            ->selectRaw('SUM(order_items.quantity) as units')
            ->selectRaw('SUM(order_items.subtotal) as revenue')
            ->groupBy('label')
            ->orderByDesc('units')
            ->limit(10)
            ->get();

        $topCustomers = (clone $activeOrders)(Order::query())
            ->selectRaw('user_id, COUNT(*) as order_count, SUM(grand_total) as spend')
            ->groupBy('user_id')
            ->orderByDesc('spend')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $user = User::withTrashed()->find($row->user_id);

                return [
                    'name' => $user?->name ?? 'Customer #'.$row->user_id,
                    'email' => $user?->email ?? '',
                    'order_count' => (int) $row->order_count,
                    'spend' => round((float) $row->spend, 2),
                ];
            });

        $topRequests = ProductRequest::query()
            ->selectRaw("COALESCE(NULLIF(product_name, ''), 'Unnamed product') as label")
            ->selectRaw('COUNT(*) as requests')
            ->groupBy('label')
            ->orderByDesc('requests')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'requests' => (int) $row->requests,
            ]);

        return compact(
            'totalCustomers', 'buyers', 'repeatBuyers', 'repeatRate',
            'revenue', 'ordersCount', 'aov', 'topProducts', 'topCustomers', 'topRequests'
        );
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\PaymentTransaction;
use App\Models\PlanChangeRequest;
use App\Models\ProductRequest;
use App\Models\ExchangeRequest;
use App\Models\AccountDeletionRequest;
use App\Models\InstallmentPayment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $this->authorize('view dashboard');

        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->sum('amount');

        // Plan lifecycle stats — the headline cards admins actually track.
        $activePlans = Order::whereIn('status', ['pending', 'processing', 'partial_paid'])->count();
        $completedPlans = Order::where('status', 'completed')->count();
        $cancelledPlans = Order::where('status', 'cancelled')->count();

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $recentUsers = User::latest()->take(10)->get();

        // Recent money movement across the whole platform.
        $recentTransactions = PaymentTransaction::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Upcoming/overdue installment exposure.
        $dueThisMonth = InstallmentPayment::where('status', 'pending')
            ->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $overduePayments = InstallmentPayment::where('status', 'overdue')->count();

        $pendingPlanChanges = PlanChangeRequest::where('status', 'pending')->count();
        $pendingProductRequests = ProductRequest::where('status', 'pending')->count();
        $pendingExchanges = ExchangeRequest::where('status', 'pending')->count();

        // Chart data: Monthly revenue for current year
        $monthlyRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $revenueByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonth[] = $monthlyRevenue[$m] ?? 0;
        }

        // Chart data: Orders grouped by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Chart data: Orders per month
        $ordersPerMonth = Order::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $ordersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $ordersByMonth[] = $ordersPerMonth[$m] ?? 0;
        }

        // Chart data: Users registered per month
        $usersPerMonth = User::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $usersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $usersByMonth[] = $usersPerMonth[$m] ?? 0;
        }

        // Month labels
        $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        return view('backend.index', compact(
            'totalProducts', 'totalOrders', 'totalUsers', 'totalRevenue',
            'activePlans', 'completedPlans', 'cancelledPlans',
            'recentOrders', 'recentUsers', 'recentTransactions',
            'dueThisMonth', 'overduePayments',
            'pendingPlanChanges', 'pendingProductRequests', 'pendingExchanges',
            'revenueByMonth', 'ordersByStatus', 'ordersByMonth', 'usersByMonth', 'monthLabels'
        ));
    }

    /**
     * AJAX endpoint: returns dashboard data as JSON for auto-refresh.
     */
    public function refreshData()
    {
        $this->authorize('view dashboard');

        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->sum('amount');

        $recentOrders = Order::with('user')->latest()->take(10)->get()->map(function ($o) {
            return [
                'id' => $o->id,
                'customer' => $o->user?->name ?? 'N/A',
                'avatar' => strtoupper(substr($o->user?->name ?? '?', 0, 1)),
                'amount' => '₦'.number_format($o->grand_total ?? 0, 0),
                'grand_total_raw' => $o->grand_total ?? 0,
                'status' => $o->status,
                'status_label' => ucwords(str_replace('_', ' ', $o->status)),
                'date' => $o->created_at->diffForHumans(),
            ];
        });

        $recentUsers = User::latest()->take(10)->get()->map(function ($u) {
            return [
                'name' => $u->name ?? 'N/A',
                'email' => $u->email,
                'avatar' => strtoupper(substr($u->name ?? '?', 0, 1)),
                'time' => $u->created_at->diffForHumans(),
            ];
        });

        $pendingPlanChanges = PlanChangeRequest::where('status', 'pending')->count();
        $pendingProductRequests = ProductRequest::where('status', 'pending')->count();
        $pendingExchanges = ExchangeRequest::where('status', 'pending')->count();
        $pendingDeletions = AccountDeletionRequest::where('status', 'pending')->count();

        $activePlans = Order::whereIn('status', ['pending', 'processing', 'partial_paid'])->count();
        $completedPlans = Order::where('status', 'completed')->count();
        $cancelledPlans = Order::where('status', 'cancelled')->count();

        $recentTransactions = PaymentTransaction::with('user')->latest()->take(8)->get()->map(function ($t) {
            return [
                'reference' => $t->transaction_reference,
                'customer' => $t->user?->name ?? 'N/A',
                'avatar' => strtoupper(substr($t->user?->name ?? '?', 0, 1)),
                'amount' => '₦'.number_format($t->amount ?? 0, 0),
                'status' => $t->status,
                'gateway' => $t->gateway,
                'date' => $t->created_at->diffForHumans(),
            ];
        });

        // Monthly revenue
        $monthlyRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $revenueByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonth[] = $monthlyRevenue[$m] ?? 0;
        }

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Orders per month
        $ordersPerMonth = Order::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $ordersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $ordersByMonth[] = $ordersPerMonth[$m] ?? 0;
        }

        // Users per month
        $usersPerMonth = User::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $usersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $usersByMonth[] = $usersPerMonth[$m] ?? 0;
        }

        // Stat change percentages (compare with last month)
        $prevMonthRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount');

        $thisMonthRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $revenueChange = $prevMonthRevenue > 0 ? round((($thisMonthRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1) : 0;

        return response()->json([
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'revenueFormatted' => '₦'.number_format($totalRevenue, 0),
            'productsFormatted' => number_format($totalProducts),
            'ordersFormatted' => number_format($totalOrders),
            'usersFormatted' => number_format($totalUsers),
            'revenueChange' => $revenueChange,
            'pendingPlanChanges' => $pendingPlanChanges,
            'pendingProductRequests' => $pendingProductRequests,
            'pendingExchanges' => $pendingExchanges,
            'pendingDeletions' => $pendingDeletions,
            'activePlans' => $activePlans,
            'completedPlans' => $completedPlans,
            'cancelledPlans' => $cancelledPlans,
            'recentTransactions' => $recentTransactions,
            'recentOrders' => $recentOrders,
            'recentUsers' => $recentUsers,
            'revenueByMonth' => $revenueByMonth,
            'ordersByMonth' => $ordersByMonth,
            'usersByMonth' => $usersByMonth,
            'ordersByStatus' => (object)$ordersByStatus,
        ]);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\PaymentTransaction;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $this->authorize('view analytics');

        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('grand_total');
        $totalUsers = User::count();
        $totalProducts = Product::count();

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Monthly revenue (completed orders)
        $monthlyRevenueRaw = Order::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $revenueByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonth[] = $monthlyRevenueRaw[$m] ?? 0;
        }

        // Orders per month
        $ordersPerMonthRaw = Order::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $ordersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $ordersByMonth[] = $ordersPerMonthRaw[$m] ?? 0;
        }

        // Users registered per month
        $usersPerMonthRaw = User::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $usersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $usersByMonth[] = $usersPerMonthRaw[$m] ?? 0;
        }

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Payment transactions by gateway
        $paymentsByGateway = PaymentTransaction::where('status', 'success')
            ->where('type', 'payment')
            ->selectRaw('gateway, COUNT(*) as count')
            ->groupBy('gateway')
            ->pluck('count', 'gateway')
            ->toArray();

        // Payment methods (installment vs full) distribution
        $paymentMethods = Order::selectRaw("CASE WHEN payment_type = 'installment' THEN 'installment' ELSE 'full_payment' END as method, COUNT(*) as count")
            ->groupBy('method')
            ->pluck('count', 'method')
            ->toArray();

        $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        return view('backend.analytics.index', compact(
            'totalOrders', 'totalRevenue', 'totalUsers', 'totalProducts',
            'recentOrders', 'revenueByMonth', 'ordersByMonth', 'usersByMonth',
            'ordersByStatus', 'paymentsByGateway', 'paymentMethods', 'monthLabels'
        ));
    }

    public function export()
    {
        $this->authorize('view analytics');

        $orders = Order::with('user')->latest()->get();

        $csv = "Order #,Customer,Amount,Status,Date\n";
        foreach ($orders as $order) {
            $csv .= "{$order->order_number},{$order->user->name},{$order->grand_total},{$order->status},{$order->created_at}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics_export.csv"',
        ]);
    }
}

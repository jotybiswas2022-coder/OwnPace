<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\Reporting\ReportingService;
use Illuminate\Http\Request;

/**
 * Reporting dashboard — sales over time, installment performance and customer
 * behavior, each with a CSV export. Replaces the old (broken) analytics page.
 */
class AdminReportingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view analytics');

        $days = $this->period($request);
        $sales = ReportingService::sales($days);
        $installments = ReportingService::installmentPerformance();
        $behavior = ReportingService::customerBehavior();

        return view('backend.reporting.index', compact('days', 'sales', 'installments', 'behavior'));
    }

    /**
     * CSV export per report. report=sales|installments|customers, plus the
     * period for sales.
     */
    public function export(Request $request)
    {
        $this->authorize('view analytics');

        $report = $request->query('report', 'sales');
        $days = $this->period($request);

        $csv = match ($report) {
            'installments' => $this->installmentsCsv(),
            'customers' => $this->customersCsv(),
            default => $this->salesCsv($days),
        };

        $filename = match ($report) {
            'installments' => 'installment_performance',
            'customers' => 'customer_behavior',
            default => 'sales_over_time',
        };

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'_'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    protected function period(Request $request): int
    {
        $days = (int) $request->query('period', 30);

        return in_array($days, ReportingService::PERIODS, true) ? $days : 30;
    }

    protected function salesCsv(int $days): string
    {
        $sales = ReportingService::sales($days);

        $csv = "Date,Revenue,Orders\n";
        foreach ($sales['labels'] as $i => $label) {
            $csv .= $label.','.number_format($sales['revenue'][$i], 2).','.$sales['orders'][$i]."\n";
        }
        $csv .= "\nTotal Revenue,".number_format($sales['revenueTotal'], 2)."\n";
        $csv .= 'Total Orders,'.$sales['orderTotal']."\n";
        $csv .= 'Average Order Value,'.number_format($sales['aov'], 2)."\n";

        return $csv;
    }

    protected function installmentsCsv(): string
    {
        $data = ReportingService::installmentPerformance();

        $csv = "Category,Count,Amount\n";
        foreach ($data['breakdown'] as $key => $row) {
            $csv .= ucfirst($key).','.$row['count'].','.number_format($row['amount'], 2)."\n";
        }

        $csv .= "\nMonth,Total Due,On-time,Late,Overdue,Defaulted\n";
        foreach ($data['months'] as $m) {
            $csv .= $m['label'].','.$m['due'].','.$m['on_time'].','.$m['late'].','.$m['overdue'].','.$m['defaulted']."\n";
        }

        return $csv;
    }

    protected function customersCsv(): string
    {
        $data = ReportingService::customerBehavior();

        $csv = "Customers,Total Buyers,Repeat Buyers,Repeat Purchase Rate,Average Order Value\n";
        $csv .= $data['totalCustomers'].','.$data['buyers'].','.$data['repeatBuyers'].','.$data['repeatRate'].'%,'.number_format($data['aov'], 2)."\n";

        $csv .= "\nTop Products,Units,Revenue\n";
        foreach ($data['topProducts'] as $p) {
            $csv .= '"'.$p->label.'",'.$p->units.','.number_format((float) $p->revenue, 2)."\n";
        }

        $csv .= "\nTop Customers,Orders,Spend\n";
        foreach ($data['topCustomers'] as $c) {
            $csv .= '"'.$c['name'].'","'.$c['email'].'",'.$c['order_count'].','.number_format($c['spend'], 2)."\n";
        }

        $csv .= "\nMost-requested Products,Requests\n";
        foreach ($data['topRequests'] as $r) {
            $csv .= '"'.$r['label'].'",'.$r['requests']."\n";
        }

        return $csv;
    }
}

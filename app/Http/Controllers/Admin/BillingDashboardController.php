<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;

class BillingDashboardController extends Controller
{
    //
    public function __invoke()
    {
        // 1. Core KPIs (Divided by 100 if converting cents to primary currency units)
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('invoice_amount') / 100,
            'pending_amount' => Invoice::where('status', 'pending')->sum('invoice_amount') / 100,
            'bills_today' => Invoice::whereDate('created_at', Carbon::today())->count(),
            'bills_30_days' => Invoice::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];

        // 2. Monthly Revenue Breakdown for Charts
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->selectRaw("SUM(invoice_amount) / 100 as revenue, DATE_FORMAT(created_at, '%b %Y') as month")
            ->groupBy('month')
            ->orderBy('created_at', 'asc')
            ->get();

        // 3. Recent Transactions Log
        $recentInvoices = Invoice::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.billing.dashboard', compact('stats', 'monthlyRevenue', 'recentInvoices'));
    }
}

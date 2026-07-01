<?php

namespace App\Http\Controllers;


use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $stats = [
            'total_revenue' => Invoice::sum('invoice_amount'),
            'partial_amount' => Invoice::where('payment_status', 'partial')->sum('received_amount'),
            'paid_amount' => Invoice::where('payment_status', 'paid')->sum('received_amount'),
            'unpaid_amount' => Invoice::where('payment_status', 'unpaid')->sum('invoice_amount'),
            'sales_today' => Invoice::whereDate('created_at', Carbon::today())->count(),
        ];

        // Monthly sales data for charts
        $monthlyRevenue = Invoice::selectRaw('MONTH(created_at) as month, SUM(invoice_amount) as total')
            ->where('invoice_status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $recentInvoices = Invoice::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'monthlyRevenue', 'recentInvoices'));
    }
}

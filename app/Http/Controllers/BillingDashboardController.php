<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Carbon;

class BillingDashboardController extends Controller
{
    //
    
        public function index()
        {
             $stats = [
            'total_revenue' => Invoice::where('payment_status', 'paid')->sum('invoice_amount'),
            'pending_amount' => Invoice::where('payment_status', 'pending')->sum('invoice_amount'),
            'overdue_count'  => Invoice::where('payment_status', 'overdue')->count(),
        ];

            $recentInvoices = Invoice::with('user')
            ->latest()
            ->take(5)
            ->get();

            return view('billing.dashboard', compact('stats', $recentInvoices));
        }
    
}

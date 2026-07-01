<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class BillingAnalyticsService
{
    /**
     * Create a new class instance.
     */
    public function getDashboardMetrics(): array
    {
        return [
            'total_revenue' => Invoice::where('payment_status', 'paid')->sum('invoice_amount'),
            'pending_amount' => Invoice::where('payment_status', 'partial')->sum('invoice_amount'),
            'unpaid_count' => Invoice::where('payment_status', 'partial')->count(),
            'monthly_earnings' => Invoice::where('payment_status', 'paid')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('invoice_amount'),
            'recent_invoices' => Invoice::with('user')
                ->latest()
                ->take(5)
                ->get(),

        

        
        ];
        
    }
}

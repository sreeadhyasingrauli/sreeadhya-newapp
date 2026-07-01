<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class BillingController extends Controller
{
    //
    public function index()
    {
        // Fetch financial records or invoices here
        $stats = [
            'total_invoiced' => Invoice::sum('invoice_amount'),
            'total_paid'     => Invoice::where('payment_status', 'paid')->sum('invoice_amount'),
            'total_unpaid'   => Invoice::where('payment_status', 'unpaid')->sum('invoice_amount'),
            'total_partial'   => Invoice::where('payment_status', 'partial')->sum('received_amount'),
            'total_overdue'  => Invoice::where('payment_status', 'overdue')->sum('invoice_amount'),
        ];

        // Fetch top 5 latest invoices mapped with client configurations
        $recentInvoices = Invoice::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentInvoices'));
       // return view('billing.index');
    }
}

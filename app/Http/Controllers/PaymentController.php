<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
 
use Carbon\Carbon;

class PaymentController extends Controller
{
    //
    public function index() : View
    {
        //
        $payments = Payment::latest()->paginate(5);
        return view('payments.index', compact('payments'));
        
    }
    public function create() : View
    {
        //
        $invoices  = Invoice::all();
        return view('payments.create',compact('allInvoices'));
        
    }
    public function store(Request $request, Invoice $invoice) : RedirectResponse
    {
        // 1. Validate incoming user input
        $validated = $request->validate([
            'amount_received' => ['required', 'numeric', 'min:0.01', 'max:' . $invoice->balance_amount],
            'payment_method' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string', 'unique:payments,transaction_id'],
            'payment_date' => ['required', 'date'],
            
        ]);

        // 2. Execute within a safe DB transaction
        DB::transaction(function () use ($invoice, $validated) {
            // Create payment record
            $invoice->payments()->create([
                'amount_received' => $validated['amount_received'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'payment_date' => $validated['payment_date'],
                 
            ]);

            // Update status based on remaining balance
            $newAmtRecd = $invoice->received_amount+$validated['amount_received']; // Recalculated due to new relationship item
            $newBalance = $invoice->balance_amount-$validated['amount_received']; // Recalculated due to new relationship item
            
            if ($newBalance <= 0) {
                $invoice->update(['payment_status' => 'paid']);
                $invoice->update(['received_amount' => $rounded = round($newAmtRecd, 2)]);
                $invoice->update(['balance_amount' => $rounded = round($newBalance, 2)]);
            } else {
                $invoice->update(['payment_status' => 'partial']);
                $invoice->update(['received_amount' => $rounded = round($newAmtRecd, 2)]);
                $invoice->update(['balance_amount' => $rounded = round($newBalance, 2)]);
            }
        });

        return redirect()->back()->with('success', 'Payment received successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RupeeConverter;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    //
    
    public function index() : View
    {
        
        $invoices = Invoice::paginate(10);
        return view('invoices.index', compact('invoices'));
    }
    public function create() : View
    {
        //
        $items = InvoiceItem::all();
        $allCustomers = Customer::all();
         $allCompanies = Company::all();
         $allProducts = Product::all();
         $allPOs  = PurchaseOrder::all();
         $allPOItems  = PurchaseOrderItem::all();
        return view('invoices.create', compact( 'allCustomers','allPOItems','allCompanies','items','allProducts','allPOs'));
        
    }
    public function store(Request $request)
    {
        // 1. Rigorous Data Validation
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_id' => 'required|exists:purchase_orders,id',
            'invoice_number' => 'required|string|max:25',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.part_number' => 'required|string',
            'items.*.inv_quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // 2. Encapsulated DB Transaction 
        $invoice = DB::transaction(function () use ($validated) {
            
            // Create the primary Invoice Header record
            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'],
                'order_id' => $validated['order_id'], // Unique Number Variant
                'invoice_number' => $validated['invoice_number'], // Unique Number Variant
                'invoice_date' => $validated['invoice_date'],
                           
                'basic_amount' => 0.00,
                'gst_amount' => 0.00,
                'invoice_amount' => 0.00, // Placeholder to update post-calculation
                
                'payment_status' => 'unpaid',
                'invoice_status' => 'active',
            ]);

            $totalAmount = 0;
            $gstValue = 0;
             $totalValue = 0;
             $totalReceived = 0;
              $totalBalance = 0;

            // Process and iterate individual purchase rows
            foreach ($validated['items'] as $item) {
                $subtotal = $item['inv_quantity'] * $item['unit_price'];
                $gst_amount =  ($subtotal * 18/100 ); 
                $total_amount =  ($subtotal + $gst_amount);
                $totalAmount += $subtotal;
                $gstValue   += $gst_amount; 
                $totalValue = ($totalAmount + $gstValue) ;
                 $totalBalance = $totalValue;

                $invoice->items()->create([
                    'part_number' => $item['part_number'],
                    'part_description' => ' ',
                    'inv_quantity' => $item['inv_quantity'],
                    'unit_price' => $item['unit_price'],
                    'uom' => ' ',
                    'sub_total' => $subtotal,
                    'gst_rate' => 0.00,
                    'gst_amount' => 0.00,
                     
                ]);
                 DB::table('invoice_items')
                ->join('purchase_order_items', 'invoice_items.part_number', '=', 'purchase_order_items.part_number')
                ->update([
                'invoice_items.part_description' => DB::raw('purchase_order_items.part_description'),
                'invoice_items.uom' => DB::raw('purchase_order_items.uom'),
                'invoice_items.updated_at' => now(), // Manually update timestamps when using DB builder
            ]);

            }

            // Sync structural grand total balance calculation back to base PO
            
            $invoice->update(['basic_amount' => $rounded = round($totalAmount, 2)]);
            $invoice->update(['gst_amount' => $rounded = round($gstValue, 2)]);
            $invoice->update(['invoice_amount' => $rounded = round($totalValue, 2)]);
             $invoice->update(['balance_amount' => $rounded = round($totalValue, 2)]);

            return $invoice;
        });

         return redirect()->route('invoices.index')
                ->withSuccess('Invoice is created successfully.');
    }
    
    // Stream or force download an identical, auto-generated PDF file
    public function preview($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
         $items = InvoiceItem::all();
        $customers = Customer::all();
       $allCompanies = Company::all();
        $products = Product::all();
        $order = PurchaseOrder::all();

        $grandTotal = $invoice->invoice_amount; // Example amount
        
         
        // Convert to words
        $amountInWords = RupeeConverter::convert($grandTotal);
        $data = [
            'invoice_no' => 'INV-2026-001',
            'amount' => $grandTotal,
            'amount_in_words' => $amountInWords
            ];
             $pdf->setPaper('a4', 'portrait');
        return view('reports.pdf', compact('invoice', 'order','customers','allCompanies','items','data','amountInWords'));
    }
    public function download($id)
    {
        // 1. Fetch the specific invoice or fail if it doesn't exist
         $invoice = Invoice::with('items')->findOrFail($id);
         $items = InvoiceItem::all();
        $allCompanies = Company::all();
        $products = Product::all();
        
        
        $customerWithInvoice = DB::table('customers')
        ->join('invoices', 'customers.customer_id', '=', 'invoices.customer_id')
         ->where('invoices.id', $id)
        ->select('customers.*', )
        ->get();
        $poWithInvoice = DB::table('purchase_orders')
        ->join('invoices', 'purchase_orders.id', '=', 'invoices.order_id')
         ->where('invoices.id', $id)
        ->select('purchase_orders.*', )
        ->get();
        
        // 2. Pass the data to your dedicated Blade layout
        $grandTotal = $invoice->invoice_amount; // Example amount
        // Convert to words
        $amountInWords = RupeeConverter::convert($grandTotal);
        $data = [
            'amount' => $grandTotal,
            'amount_in_words' => $amountInWords
            ];
        // Load the view and bind data matrix
        // $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice','poWithInvoice','products','customerWithInvoice','allCompanies','items','data','amountInWords'));
        // 3. Set custom paper size if needed (Optional)
        $pdf->setPaper('a4', 'portrait');

        // 4. Force the file to download with a unique file name
       return $pdf->download('invoice-' . $invoice->customer_id . '.pdf');
              
        
    }
    public function show(Invoice $invoice): View
    {
        //
        // Fetch your invoice here
        //$invoice = Invoice::findOrFail($id);
        
        return view('invoices.show', compact('invoice'));
        return redirect()->route('invoices.index')
                ->withSuccess('Payment against this Invoice is created successfully.');
         
    }
    public function edit(Request $request) : View
    {
        //
        
    }
    public function update(Request $request) : RedirectResponse
    {
        //
        
    }
    public function destroy(Invoice $invoice) : RedirectResponse
    {
        //
        $invoice->update(['invoice_status' => 'Deleted']);
        $invoice->delete();

        return redirect()->route('invoices.index')
                ->withSuccess('Invoice is deleted successfully.');
    }
}

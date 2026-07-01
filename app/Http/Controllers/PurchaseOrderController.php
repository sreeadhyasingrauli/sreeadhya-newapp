<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\PurchaseOrderService;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;



class PurchaseOrderController extends Controller
{
    //
    // 
    public function index() : View
    {
        //
        $order = PurchaseOrder::latest()->paginate(5);
        return view('purchase-orders.index', compact('order'));
        
    }
    public function create() : View
    {
        //
        $items = PurchaseOrderItem::all();
        $allCustomers = Customer::all();
         $allProducts = Product::all();
         $allCompanies = Company::all();
        return view('purchase-orders.create', compact( 'allCustomers','allCompanies','items','allProducts'));
        
    }
     // Inject the custom Service Class via dependency injection
    

    public function store(Request $request)
    {
        // 1. Rigorous Data Validation
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'po_number' => 'required|string|max:25',
            'po_date' => 'required|date',
            'del_end_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.part_number' => 'required|string',
             'items.*.part_description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.uom' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // 2. Encapsulated DB Transaction 
        $order = DB::transaction(function () use ($validated) {
            
            // Create the primary Purchase Order Header record
           $order = PurchaseOrder::create([
                'customer_id' => $validated['customer_id'],
                'po_number' => $validated['po_number'], // Unique Number Variant
                'po_date' => $validated['po_date'],
                'del_end_date' => $validated['del_end_date'],
                'basic_value' => 0.00,
                'gst_value' => 0.00,
                'total_value' => 0.00, // Placeholder to update post-calculation
                'status' => 'draft',
            ]);

            $totalAmount = 0;
            $gstValue = 0;
             $totalValue = 0;

            // Process and iterate individual purchase rows
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $gst_amount =  ($subtotal * 18/100 ); 
                $total_amount =  ($subtotal + $gst_amount);
                $totalAmount += $subtotal;
                $gstValue   += $gst_amount; 
                $totalValue = ($totalAmount + $gstValue) ;
                
                $order->items()->create([
                    'part_number' => $item['part_number'],
                    'part_description' => $item['part_description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'uom' => $item['uom'],
                    'subtotal' => $subtotal,
                    'gst_amount' => $gst_amount,
                    'total_amount' => $total_amount
                ]);
            }

            // Sync structural grand total balance calculation back to base PO
            
            $order->update(['basic_value' => $rounded = round($totalAmount, 2)]);
            $order->update(['gst_value' => $rounded = round($gstValue, 2)]);
            $order->update(['total_value' => $rounded = round($totalValue, 2)]);

            return $order;
        });

         return redirect()->route('purchase-orders.index')
                ->withSuccess('Purchase Order is created successfully.');
    }
    public function generateOrderAcceptance($id)
    {
        $order = PurchaseOrder::with('items')->findOrFail($id);
        $products = Product::all(); 
        $allCompanies = Company::all();
        $customerWithPO = DB::table('customers')
        ->join('purchase_orders', 'customers.customer_id', '=', 'purchase_orders.customer_id')
         ->where('purchase_orders.id', $id)
        ->select('customers.*', )
        ->get();
        // Load the view and pass the order data
        $pdf = Pdf::loadView('pdf.order-acceptance', compact('order','customerWithPO','allCompanies','products'));

        // Option 1: Download the PDF directly to user's computer
        return $pdf->download('order-acceptance-' . $order->id . '.pdf');

        // Option 2: Stream the PDF in the browser
        // return $pdf->stream('order-acceptance-' . $order->id . '.pdf');
    }

    public function show(Request $request) : View
    {
        //
         
    }
    public function edit(Request $request) : View
    {
        //
        
    }
    public function update(Request $request) : RedirectResponse
    {
        //
        
    }
    public function destroy(PurchaseOrder $order) : RedirectResponse
    {
        //
         $order->delete();

        return redirect()->back()
                ->withSuccess('Purchase Order is deleted successfully.');
        
    }

}





<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
 

class OrderController extends Controller
{
    //
    public function index(): View
    {
        // Fetch all customers from the database
        $customers = Customer::all();

        // Pass data to resources/views/customers/index.blade.php
        return view('customers.index', compact('customers'));
    }
    public function generateOrderAcceptance($id)
    {
        $order = PurchaseOrder::with('items')->findOrFail($id);
        $customers = Customer::all();  //
        $products = Product::all(); 
        // Load the view and pass the order data
        $pdf = Pdf::loadView('pdf.order-acceptance', compact('order','customers','products'));

        // Option 1: Download the PDF directly to user's computer
        return $pdf->download('order-acceptance-' . $order->id . '.pdf');

        // Option 2: Stream the PDF in the browser
        // return $pdf->stream('order-acceptance-' . $order->id . '.pdf');
    }
    public function destroy(PurchaseOrder $order) : RedirectResponse
    {
        //
         $order->delete();

        return redirect()->back()
                ->withSuccess('Purchase Order is deleted successfully.');
        
    }
}

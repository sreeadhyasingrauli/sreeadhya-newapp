<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
 

use Illuminate\Http\Request;


class OfferController extends Controller
{
    //
    public function index() : View
    {
        //
        $offer = Offer::latest()->paginate(5);
        return view('offers.index', compact('offer'));
        
    }
    public function create() : View
    {
        //
        $items = OfferItem::all();
        $allCompanies = Company::all();
        $allCustomers = Customer::all();
         $allProducts = Product::all();
        return view('offers.create', compact( 'allCustomers','allCompanies','items','allProducts'));
        
    }
    public function store(Request $request) :  RedirectResponse
    {
        // 1. Validate request
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'offer_number' => 'required|string',
            'offer_date' => 'required|date',
            'valid_until' => 'required|date',
            'payment_terms' => 'required|string',
            'gst_terms' => 'required|string',
            'delivery_terms' => 'required|string',
            'pf_terms' => 'required|string',
            'pricebasis_terms' => 'required|string',
            'guarantee_terms' => 'required|string',
            'ld_terms' => 'required|string',
            'other_terms' => 'required|string',
            'items' => 'required|array',
            'items.*.part_number' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',

            
        ]);
        // 2. Calculate Totals
        $subtotal = collect($validated['items'])->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
        $gst_amount = $subtotal * 0.18; // Example 18% tax
        $total = $subtotal + $gst_amount;
        
        // 3. Save Offer to Database
        $offer = Offer::create([
            'customer_id' => $validated['customer_id'],
            'offer_number' => $validated['offer_number'],
            'offer_date' => $validated['offer_date'],
            'valid_until' => $validated['valid_until'],
            'payment_terms' => $validated['payment_terms'],
            'gst_terms' => $validated['gst_terms'],
            'delivery_terms' => $validated['delivery_terms'],
            'pf_terms' => $validated['pf_terms'],
            'pricebasis_terms' => $validated['pricebasis_terms'],
            'guarantee_terms' => $validated['guarantee_terms'],
            'ld_terms' => $validated['ld_terms'],
            'other_terms' => $validated['other_terms'],
            'subtotal' => $subtotal,
            'gst_amount' => $gst_amount,
            'total_amount' => $total,
        ]);
        
       
        foreach ($validated['items'] as $item) {
            $offer->items()->create([
                'part_number' => $item['part_number'],
                'part_description' => '   ',
                'quantity' => $item['quantity'],
                'uom' =>'  ',
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
            ]);
            DB::table('offer_items')
            ->join('products', 'offer_items.part_number', '=', 'products.part_number')
            ->update([
                'offer_items.part_description' => DB::raw('products.part_description'),
                'offer_items.uom' => DB::raw('products.uom'),
                'offer_items.updated_at' => now(), // Manually update timestamps when using DB builder
            ]);

        

        
   

       

        
        }

       // return response()->json(['message' => 'Offer created successfully']);
       return redirect()->route('offers.index')
                ->withSuccess('Offer is created successfully.');
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
    public function destroy(Offer $offer) : RedirectResponse
    {
        //
         $offer->delete();

        return redirect()->back()
                ->withSuccess('Offer is deleted successfully.');
        
    }

    public function download($id)
    {
        // Fetch the specific offer from the database
        $offer = Offer::with('items')->findOrFail($id);
        $offers = Offer::with('product')->get();
        $allCompanies = Company::all();
        

        $customerWithOffers = DB::table('customers')
        ->join('offers', 'customers.customer_id', '=', 'offers.customer_id')
         ->where('offers.offer_id', $id)
        ->select('customers.*', 'offers.offer_number as offer_number', 'offers.offer_date')
        ->get();
         // Load the view and pass the data to it
        $pdf = Pdf::loadView('pdf.offer', compact('offer','customerWithOffers','allCompanies'));

        // Return the PDF to the browser (stream) or download it
        return $pdf->download('customer_offer_'.$id.'.pdf');

        // Optional: Check if the user is authorized to download this offer
        // Gate::authorize('view', $offer);

        // Define the file path (e.g., assuming it is stored in storage/app/offers)
        //$filePath = 'offers/' . $offer->file_name;

        // Verify the file actually exists on the disk
        //if (!Storage::exists($filePath)) {
      //      abort(404, 'File not found.');
       // }

        // Return the download stream, renaming the downloaded file on the fly
       // return Storage::download($filePath, 'special-offer-' . $offer->id . '.pdf', [
        //    'Content-Type' => 'application/pdf',
        //]);
         
         
    }
    public function generateOfferPdf($id)
    {
        // Retrieve the offer data from the database
        $offer = Offer::findOrFail($id);
        $customer = Customer::with('offers')->findOrFail($id);
         $allCompanies = Company::all();
        // Load the view and pass the data to it
        $pdf = Pdf::loadView('offer', compact('offer','customer','allCompanies'));

        // Return the PDF to the browser (stream) or download it
        return $pdf->download('customer_offer_'.$id.'.pdf');
    }
    public function generateOffer($id)
    {
        // Retrieve the offer data from the database
        $offer = Offer::findOrFail($id);
        $customer = Customer::with('offers')->findOrFail($id);
        $allCompanies = Company::all();
        // Load the view and pass the data to it
        $pdf = Pdf::loadView('offer', compact('offer','customer','allCompanies'));

        // Return the PDF to the browser (stream) or download it
        return $pdf->download('customer_offer_'.$id.'.pdf');
    }
}

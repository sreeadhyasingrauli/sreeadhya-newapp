<?php

namespace App\Http\Controllers;

use App\Models\Budgetory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuoteController extends Controller
{
     public function index() : View
    {
        //
        
    }
    public function create() : View
    {
        
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'expires_at' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        
            $subtotal = 0;
        // 1. Initialize the base quotation
            $quote = Quote::create([
                'quote_number' => 'QT-' . strtoupper(uniqid()),
                'customer_id' => $validated['customer_id'],
                'expires_at' => $validated['expires_at'],
                'status' => 'draft',
            ]);

            // 2. Process and link line items
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $subtotal += $lineTotal;

                $quote->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $lineTotal,
                ]);
            }
            // 3. Compute final tax rules (e.g., 15% Tax)
            $taxAmount = $subtotal * 0.15;
            $total = $subtotal + $taxAmount;

            // 4. Update parent calculations
            $quote->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
            ]);

            return $quote;
        
    

    }        
    
    
    public function show(Budgetory $budgetory) : View
    {
        
    }
    public function edit(Budgetory $budgetory) : View
    {
        //
       
    }

    /**
     * Update the specified resource in storage.
     */
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budgetory $budgetory) : RedirectResponse
    {
        
    }
}

<?php

namespace App\Services;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderService
{
    /**
     * Create a new class instance.
     */
     public function create(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            // Generate unique PO sequence number
            $poNumber = 'PO-' . strtoupper(Str::random(4)) . '-' . time();

            // 1. Create the base Purchase Order header
            $purchaseOrder = PurchaseOrder::create([
                'customer_id' => $data['customer_id'],
                'po_number'   => $poNumber,
                'issue_date'  => $data['issue_date'] ?? now()->toDateString(),
                'status'      => 'draft',
                'total_amount'=> 0, 
            ]);

            $totalAmount = 0;

            // 2. Loop through individual items and process calculations
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $product->price;

                $purchaseOrder->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // 3. Update final aggregated total balance
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            return $purchaseOrder->load('items');
        });
    }

}

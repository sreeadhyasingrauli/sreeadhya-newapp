<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    //
    protected $fillable = ['customer_id', 'po_number', 'po_date', 'del_end_date','basic_value', 'gst_value','total_value','status'];

    public function customer(): BelongsTo 
    {
      return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany 
    {
       return $this->hasMany(PurchaseOrderItem::class,'purchase_order_id');
    }
    public function product(): BelongsTo
    {
        // Assumes your offers table has a 'product_id' foreign key
        return $this->belongsTo(Product::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }
    public function attributes(): array
    {
            return [
                  'items.*.part_number' => 'item part_number',
                  'items.*.part_description' => 'item description',
                    'items.*.quantity' => 'item quantity',
                    'items.*.uom' => 'item uom',
                    'items.*.unit_price' => 'item unit price',
            ];
    }
}

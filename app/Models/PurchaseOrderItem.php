<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PurchaseOrderItem extends Model
{
    //
    protected $fillable = ['purchase_order_id', 'part_number','part_description', 'quantity', 'uom','unit_price', 'subtotal','gst_amount','total_amount'];

    public function purchaseOrder(): BelongsTo 
    {
         return $this->belongsTo(PurchaseOrder::class);
    }
    public function invoice_items() 
    {
        return $this->hasMany(InvoiceItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    //
    protected $fillable = [ 'invoice_id','part_number','part_description',
               'inv_quantity', 'uom', 'unit_price', 'sub_total','gst_rate',  'gst_amount',
        
    ];
    public function invoice(): BelongsTo 
    {
         return $this->belongsTo(Invoice::class);
    }
    public function purchase_order_items() 
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }
}

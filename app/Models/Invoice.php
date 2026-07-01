<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Invoice extends Model
{
    //
    protected $fillable = ['invoice_number','customer_id','order_id',
         'invoice_date', 'basic_amount','gst_amount','invoice_amount','received_amount',
        'balance_amount','invoice_status','payment_status'
        
    ];
    public function items(): HasMany { return $this->hasMany(InvoiceItem::class); }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function customers(): HasMany
            {
    return $this->hasMany(Customer::class);
    }
    //  CORRECT
    
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function getBalanceDueAttribute(): float
    {
        return $this->invoice_amount - $this->payments()->sum('amount_received');
    }
     
}

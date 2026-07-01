<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Offer extends Model
{
    //
    protected $primaryKey = 'offer_id';
    protected $fillable = [ 'customer_id',
        'offer_number','offer_date',
        'valid_until', 'payment_terms','gst_terms','delivery_terms','pf_terms',
        'pricebasis_terms','guarantee_terms','ld_terms','other_terms',
        'subtotal',
        'gst_amount',
        'total_amount',
        
       
    ];
     public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id' );
    }

    public function items(): HasMany
    {
         return $this->hasMany(OfferItem::class, 'offer_id');
         
    }
    public function product(): BelongsTo
    {
        // Assumes your offers table has a 'product_id' foreign key
        return $this->belongsTo(Product::class);
    }
    public function attributes(): array
    {
            return [
                  'items.*.part_number' => 'item part_number',
                  'items.*.part_description' => 'item description',
                    'items.*.quantity' => 'item quantity',
                    'items.*.uom' => 'item uom',
                    'items.*.price' => 'item price',
            ];
    }
    
}

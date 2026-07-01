<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Customer extends Model
{
    //
     protected $primaryKey = 'customer_id';
    protected $fillable = [
        
        'address_to',
        'customer_name',
        'customer_short_name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pin_code',
        'country',
        'contact_number',
        'email',
        'website',
        'pan_number',
        'gst_number',
    ];
    public function purchaseOrders(): HasMany 
    {
      return $this->hasMany(PurchaseOrder::class);
    }
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}

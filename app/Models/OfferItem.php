<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferItem extends Model
{
    //
     
    protected $fillable = [ 'offer_id','part_number','part_description',
               'quantity','uom',
        'price',
        'total',
        
    ];
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}

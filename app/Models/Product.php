<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
     
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'part_number',
        'part_description','make', 'price','uom','hsn_code','gst_rate'
       
    ];
}

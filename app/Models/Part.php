<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    //
    protected $primaryKey = 'part_number';
    protected $fillable = [
        'part_number',
        'part_description',
        'make', 'uom',
        'price',
        'hsn_code', 'gst_rate'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //

  
    protected $fillable = [
        'company_name',
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
        'gst_number', 'bank_name', 'account_number','ifsc_code','branch_name'
    ];
}

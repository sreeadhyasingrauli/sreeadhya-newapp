<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budgetory extends Model
{
    //
    protected $fillable = [
        'budgetory_number',
        'budgetory_date',
        'customer_name',
        'address_to',
        'budget_description',
        'budget_amount',
        'payment_terms',
        'delivery_terms',
        'warranty_terms',
        'offer_validity',
        'validity_end_date',
        'status'
    ];
}

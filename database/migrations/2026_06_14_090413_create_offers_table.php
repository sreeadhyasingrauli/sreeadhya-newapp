<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id('offer_id');
            $table->foreignId('customer_id');
            $table->string('offer_number');
            $table->date('offer_date');
            $table->date('valid_until');
            $table->string('payment_terms');
            $table->string('gst_terms');
            $table->string('delivery_terms');
            $table->string('pf_terms');
            $table->string('pricebasis_terms');
            $table->string('guarantee_terms');
            $table->string('ld_terms');
            $table->string('other_terms');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('gst_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

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
        Schema::create('budgetories', function (Blueprint $table) {
            $table->id();
            $table->integer('budgetory_number')->unique();
             $table->date('budgetory_date');
            $table->string('customer_name');
           $table->string('address_to');
           $table->string('budget_description')->nullable();
            $table->decimal('budget_amount', 15, 2);
            $table->string('payment_terms')->nullable();
              $table->string('delivery_terms')->nullable();
              $table->string('warranty_terms')->nullable();
               $table->string('offer_validity')->nullable();
               $table->date('validity_end_date')->nullable();
               $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgetories');
    }
};

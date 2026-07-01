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
        Schema::create('purchase_orders', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('po_number')->unique();
            $table->date('po_date');
             $table->date('del_end_date');
            $table->decimal('basic_value', 15, 2)->default(0.00);
             $table->decimal('gst_value', 15, 2)->default(0.00);
              $table->decimal('total_value', 15, 2)->default(0.00);
            $table->string('status')->default('draft'); // draft, sent, approved, completed
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

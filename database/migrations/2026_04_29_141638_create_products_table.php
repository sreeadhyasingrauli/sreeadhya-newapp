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
        Schema::create('products', function (Blueprint $table) {
           // This automatically creates an unsigned bigint auto-incrementing PRIMARY KEY named 'id'
            $table->id('product_id'); 
            // Replaced primary() with unique()
            $table->string('part_number')->unique(); 
            $table->string('part_description');
            $table->string('make');
            $table->decimal('price', 15, 2);
            $table->string('uom');
            $table->unsignedBigInteger('hsn_code');
            $table->decimal('gst_rate', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

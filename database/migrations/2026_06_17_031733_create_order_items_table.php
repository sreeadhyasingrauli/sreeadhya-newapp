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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Foreign keys referencing parent entities
            $table->foreignId('order_id');
            $table->string('part_number');
            $table->string('part_description');
            // Snapshot of data at the time of purchase
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Single item price
            $table->decimal('total', 10, 2); // quantity * price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

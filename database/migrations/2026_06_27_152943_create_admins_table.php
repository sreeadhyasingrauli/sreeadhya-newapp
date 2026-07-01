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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Admin's name
            $table->string('email')->unique(); // Unique email
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('password'); // Password
            $table->rememberToken(); // Remember token for "remember me" functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};

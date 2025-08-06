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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who owns the payment method
            $table->string('bank_name')->unique(); // Name of the payment method (e.g., Credit Card, PayPal)
            $table->longText('details')->nullable(); // Additional details or configuration for the payment method
            $table->boolean('is_active')->default(true); // Whether the payment method is active    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

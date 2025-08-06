<?php

// database/migrations/xxxx_xx_xx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\VendorApplication;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer placing the order
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');

            // Vendor from whom the order is placed
            $table->foreignIdFor(VendorApplication::class)->constrained()->onDelete('cascade');

            $table->text('special_instructions')->nullable();
            $table->timestamp('expected_receive_time')->nullable();
            $table->boolean('delivery_option')->default(true); // true = delivery, false = pickup

            // Contact information
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();

            // Delivery location
            $table->string('delivery_area')->nullable();
            $table->string('delivery_address')->nullable();

            // Payment
            $table->string('payment_method')->nullable(); // e.g., cash, card
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2);

            // Order status
            $table->string('status')->default('pending'); // e.g., pending, preparing, completed

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

<?php

use App\Models\Food;
use App\Models\Order;
use App\Models\User;
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
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Order::class);
            $table->foreignIdFor(Food::class);
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('delivery_charge', 8, 2);
            $table->decimal('earn_price', 8, 2);
            $table->string('order_status')->default('pending');
            $table->string('payment_method');
            $table->boolean('delivery_option');
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

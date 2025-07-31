<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\VendorApplication;
use App\Models\Food;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class); // Authenticated user
            $table->foreignIdFor(VendorApplication::class); // Homestaurant
            $table->foreignIdFor(Food::class); // Food item
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2); // Final price at the time of add (for consistency if food price changes)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

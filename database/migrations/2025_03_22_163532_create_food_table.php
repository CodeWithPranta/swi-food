<?php

use App\Models\Category;
use App\Models\Unit;
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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Unit::class);
            $table->string('name');
            $table->string('slug');
            $table->json('images');
            $table->longText('description');
            $table->bigInteger('quantity')->default(0);
            $table->decimal('production_cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};

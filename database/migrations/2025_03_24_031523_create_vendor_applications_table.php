<?php

use App\Models\Profession;
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
        Schema::create('vendor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('kitchen_name')->unique();
            $table->string('cover_photo')->nullable();
            $table->string('chef_name');
            $table->json('attachments');
            $table->foreignIdFor(Profession::class);
            $table->string('phone_number');
            $table->longText('description');
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};

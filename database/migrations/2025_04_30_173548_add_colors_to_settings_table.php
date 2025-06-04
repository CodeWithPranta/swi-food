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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('primary_bg_color')->nullable();
            $table->string('primary_text_color')->nullable();
            $table->string('secondary_bg_color')->nullable();
            $table->string('secondary_text_color')->nullable();
            $table->string('menu_card_bg_image')->nullable();
            $table->string('hover_bg_color')->nullable();
            $table->string('hover_text_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['primary_bg_color', 'primary_text_color', 'secondary_bg_color', 'secondary_text_color', 'menu_card_bg_image', 'hover_bg_color', 'hover_text_color']);
        });
    }
};

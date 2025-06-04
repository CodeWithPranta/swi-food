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
            $table->string('h_reg_image')->nullable();
            $table->string('h_reg_title')->nullable();
            $table->text('h_reg_paragraph')->nullable();
            $table->string('h_reg_btn_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['h_reg_image', 'h_reg_title', 'h_reg_paragraph', 'h_reg_btn_text']);
        });
    }
};

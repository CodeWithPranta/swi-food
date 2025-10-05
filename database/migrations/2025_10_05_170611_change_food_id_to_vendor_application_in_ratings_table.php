<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('ratings', function (Blueprint $table) {
        //$table->dropForeign(['food_id']); // drop old foreign key
        $table->dropColumn('food_id');    // drop the old column

        $table->foreignId('vendor_application_id')
              ->constrained()
              ->cascadeOnDelete();
    });
}

public function down()
{
    Schema::table('ratings', function (Blueprint $table) {
        $table->dropForeign(['vendor_application_id']);
        $table->dropColumn('vendor_application_id');

        $table->foreignIdFor(Food::class);
    });
}

};

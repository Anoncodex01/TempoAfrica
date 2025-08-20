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
        Schema::table('houses', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
            $table->decimal('fee', 10, 2)->change();
            $table->decimal('booking_price', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->integer('price')->change();
            $table->integer('fee')->change();
            $table->integer('booking_price')->change();
        });
    }
}; 
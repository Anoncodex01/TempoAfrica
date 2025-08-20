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
        Schema::table('bookings', function (Blueprint $table) {
            // Change checked_in_at and checked_out_at from date to timestamp
            $table->timestamp('checked_in_at')->nullable()->change();
            $table->timestamp('checked_out_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Revert back to date if needed
            $table->date('checked_in_at')->nullable()->change();
            $table->date('checked_out_at')->nullable()->change();
        });
    }
}; 
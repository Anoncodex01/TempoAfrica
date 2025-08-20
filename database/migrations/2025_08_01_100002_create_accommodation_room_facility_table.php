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
        Schema::create('accommodation_room_facility', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_room_id');
            $table->unsignedBigInteger('facility_id');
            $table->timestamps();

            $table->foreign('accommodation_room_id')->references('id')->on('accommodation_rooms')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            
            $table->unique(['accommodation_room_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_room_facility');
    }
}; 
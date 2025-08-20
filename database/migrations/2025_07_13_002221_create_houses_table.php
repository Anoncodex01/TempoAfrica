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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('registration_number');
            $table->string('unique_number')->nullable();
            $table->string('currency');
            $table->string('price_duration');
            $table->integer('price')->nullable()->default(0);
            $table->integer('fee')->nullable()->default(0);
            $table->integer('minimum_rent_duration')->default(false);
            $table->integer('booking_price')->nullable()->default(0);
            $table->integer('number_of_rooms')->nullable()->default(1);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_booked')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('has_water')->default(false);
            $table->boolean('has_electricity')->default(false);
            $table->boolean('has_fence')->default(false);
            $table->boolean('has_public_transport')->default(false);
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('booked_by')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('country_id')->nullable();
            $table->string('province_id')->nullable();
             $table->string('district_id')->nullable();
            $table->string('street_id')->nullable();
            $table->double('latitude', 12, 8)->nullable();
            $table->double('longitude', 12, 8)->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};

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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('registration_number');
            $table->string('unique_number')->nullable();
            $table->string('currency');
            $table->string('minimum_price_duration');
            $table->integer('minimum_price')->nullable()->default(0);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('category')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('street_id')->nullable();
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
        Schema::dropIfExists('accommodations');
    }
};
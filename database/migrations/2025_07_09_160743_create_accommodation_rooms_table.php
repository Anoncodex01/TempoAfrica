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
        Schema::create('accommodation_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('currency');
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_available')->default(false);
            $table->integer('price')->nullable();
            $table->string('price_duration')->nullable();
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->integer('accommodation_id')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_rooms');
    }
};

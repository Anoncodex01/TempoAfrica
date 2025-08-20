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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('unique_number')->nullable();
            $table->integer('house_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('currency');
            $table->string('price_duration');
            $table->integer('price')->nullable()->default(0);
            $table->boolean('is_visible')->default(false);
            $table->string('category')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
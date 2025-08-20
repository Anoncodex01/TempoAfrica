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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_paid')->default(false);
            $table->string('reference')->nullable();
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('currency');
            $table->integer('price')->nullable()->default(0);
            $table->integer('amount')->nullable()->default(0);
            $table->integer('amount_paid')->nullable()->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->integer('pacs')->nullable()->default(1);
            $table->integer('accommodation_id')->nullable();
            $table->integer('accommodation_room_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->boolean('is_checked_in')->nullable()->default(false);
            $table->boolean('is_checked_out')->nullable()->default(false);
            $table->boolean('is_cancelled')->nullable()->default(false);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
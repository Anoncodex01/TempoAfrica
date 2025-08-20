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
        Schema::create('house_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained('houses')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->boolean('is_paid')->default(false);
            $table->string('reference')->nullable();
            $table->string('payment_token')->nullable();
            $table->text('payment_url')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('currency', 10)->default('TZS');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            // REMOVED: is_checked_in, is_checked_out, is_cancelled, from_date, to_date, checked_in_at, checked_out_at
            // These are NOT needed for house information access
            $table->timestamp('paid_at')->nullable();
            $table->string('receipt_url')->nullable();
            $table->string('receipt_filename')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('customer_id');
            $table->index('house_id');
            $table->index('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_bookings');
    }
};

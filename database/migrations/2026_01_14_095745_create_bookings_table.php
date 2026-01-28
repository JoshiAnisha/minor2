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

            // The patient making the booking (logged-in user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // The service being booked
            $table->foreignId('service_id')->constrained()->onDelete('cascade');

            // Simple booking date (used in views)
            $table->date('date');

            // Booking status used across the UI
            $table->enum('status', ['upcoming', 'completed', 'cancelled'])->default('upcoming');

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
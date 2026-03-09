<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Bookings table uses booking_id as primary key; Booking model updated to match. No schema change. */
    public function up(): void
    {
        // No-op: table already has booking_id as PK; App\Models\Booking has $primaryKey = 'booking_id'
    }

    public function down(): void
    {
        // No-op
    }
};

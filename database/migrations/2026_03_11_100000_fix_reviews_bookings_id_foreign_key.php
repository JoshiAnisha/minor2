<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix reviews.bookings_id: it must reference bookings.booking_id, not caregivers.id.
     * The existing constraint incorrectly references caregivers(id).
     */
    public function up(): void
    {
        if (!Schema::hasTable('reviews') || !Schema::hasColumn('reviews', 'bookings_id')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        // Drop the wrong foreign key (reviews_bookings_id_foreign -> caregivers)
        try {
            DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_bookings_id_foreign');
        } catch (\Throwable $e) {
            // Constraint might have different name or not exist
            if (str_contains($e->getMessage(), '1091') || str_contains($e->getMessage(), 'check that it exists')) {
                // Ignore "check that column/key exists"
            } else {
                throw $e;
            }
        }

        // Add correct foreign key: reviews.bookings_id -> bookings.booking_id
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('bookings_id')->references('booking_id')->on('bookings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        if (!Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['bookings_id']);
        });
    }
};

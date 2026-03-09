<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reviews') || !Schema::hasColumn('reviews', 'bookings_id')) {
            return;
        }
        DB::statement('ALTER TABLE reviews MODIFY COLUMN bookings_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'bookings_id')) {
            DB::statement('ALTER TABLE reviews MODIFY COLUMN bookings_id BIGINT UNSIGNED NOT NULL');
        }
    }
};

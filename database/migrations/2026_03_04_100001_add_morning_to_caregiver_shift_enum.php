<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('caregiver_shift_times')) {
            return;
        }
        try {
            DB::statement("ALTER TABLE caregiver_shift_times MODIFY COLUMN shift ENUM('Morning', 'Day', 'Night', 'Both') NOT NULL");
        } catch (QueryException $e) {
            // Already applied or column definition unchanged - skip
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('caregiver_shift_times')) {
            DB::statement("ALTER TABLE caregiver_shift_times MODIFY COLUMN shift ENUM('Day', 'Night', 'Both') NOT NULL");
        }
    }
};

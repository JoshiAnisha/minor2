<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure caregivers table has profile_photo_path (same as certificate_path).
     */
    public function up(): void
    {
        if (!Schema::hasTable('caregivers')) {
            return;
        }
        if (Schema::hasColumn('caregivers', 'profile_photo_path')) {
            return;
        }
        DB::statement('ALTER TABLE caregivers ADD COLUMN profile_photo_path VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (Schema::hasColumn('caregivers', 'profile_photo_path')) {
            DB::statement('ALTER TABLE caregivers DROP COLUMN profile_photo_path');
        }
    }
};

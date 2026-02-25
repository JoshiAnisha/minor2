<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services')) {
            DB::statement('UPDATE services SET base_price = 0 WHERE base_price IS NULL');
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE services MODIFY base_price DECIMAL(12,2) NOT NULL DEFAULT 0');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE services MODIFY base_price FLOAT NOT NULL');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE service_requests MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending'");
        }
        // SQLite and others: status is stored as string, 'rejected' will work
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE service_requests MODIFY COLUMN status ENUM('pending', 'accepted', 'completed') DEFAULT 'pending'");
        }
    }
};

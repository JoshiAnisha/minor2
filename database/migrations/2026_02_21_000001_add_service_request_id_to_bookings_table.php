<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('bookings', 'service_request_id')) {
            return;
        }
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_request_id');
        });
    }
};

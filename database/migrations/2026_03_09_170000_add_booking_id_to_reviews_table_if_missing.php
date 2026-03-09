<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reviews')) {
            return;
        }
        if (Schema::hasColumn('reviews', 'booking_id')) {
            return;
        }
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->after('service_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('set null');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'booking_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['booking_id']);
            });
        }
    }
};

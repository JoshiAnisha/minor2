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
        Schema::table('caregivers', function (Blueprint $table) {
        $table->enum('preferred_shift', ['Day', 'Night', 'Regular'])->nullable();
        $table->time('available_time')->nullable();
        $table->string('available_day')->nullable();
        $table->string('available_service')->nullable();
        $table->date('available_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caregivers', function (Blueprint $table) {
            $table->dropColumn([
            'preferred_shift',
            'available_time',
            'available_day',
            'available_service',
            'available_date'
        ]);
        });
    }
};

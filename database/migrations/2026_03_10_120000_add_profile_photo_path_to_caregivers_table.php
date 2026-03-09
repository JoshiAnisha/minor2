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
        if (!Schema::hasTable('caregivers')) {
            return;
        }
        if (Schema::hasColumn('caregivers', 'profile_photo_path')) {
            return;
        }
        Schema::table('caregivers', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('caregivers', 'profile_photo_path')) {
            Schema::table('caregivers', function (Blueprint $table) {
                $table->dropColumn('profile_photo_path');
            });
        }
    }
};

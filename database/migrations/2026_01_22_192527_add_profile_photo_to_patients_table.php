<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('patients', 'profile_photo')) {
            return;
        }
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'notes')) {
                $table->string('profile_photo')->nullable()->after('notes');
            } else {
                $table->string('profile_photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });
    }
};

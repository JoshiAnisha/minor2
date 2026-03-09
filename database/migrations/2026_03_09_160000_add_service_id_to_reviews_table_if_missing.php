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
        if (Schema::hasColumn('reviews', 'service_id')) {
            return;
        }
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('user_id')->constrained('services')->onDelete('set null');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'service_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['service_id']);
            });
        }
    }
};

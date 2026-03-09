<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_requests')) {
            Schema::table('service_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('service_requests', 'start_date')) {
                    $table->date('start_date')->nullable()->after('preferred_time');
                }
                if (!Schema::hasColumn('service_requests', 'end_date')) {
                    $table->date('end_date')->nullable()->after('start_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('service_requests')) {
            Schema::table('service_requests', function (Blueprint $table) {
                if (Schema::hasColumn('service_requests', 'start_date')) {
                    $table->dropColumn('start_date');
                }
                if (Schema::hasColumn('service_requests', 'end_date')) {
                    $table->dropColumn('end_date');
                }
            });
        }
    }
};

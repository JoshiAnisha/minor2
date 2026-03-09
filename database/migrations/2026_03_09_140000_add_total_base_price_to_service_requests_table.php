<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_requests')) {
            return;
        }
        if (Schema::hasColumn('service_requests', 'total_base_price')) {
            return;
        }
        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('total_base_price', 12, 2)->nullable()->after('end_date');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('service_requests') && Schema::hasColumn('service_requests', 'total_base_price')) {
            Schema::table('service_requests', function (Blueprint $table) {
                $table->dropColumn('total_base_price');
            });
        }
    }
};

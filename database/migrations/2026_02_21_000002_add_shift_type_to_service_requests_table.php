<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('service_requests', 'shift_type')) {
            return;
        }
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('shift_type')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('shift_type');
        });
    }
};

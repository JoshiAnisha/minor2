<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('services', 'start_date')) {
            return;
        }
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'service_type')) {
                $table->date('start_date')->nullable()->after('service_type');
            } else {
                $table->date('start_date')->nullable();
            }
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'category')) {
                $table->string('category')->nullable()->after('service_type');
            }
            if (!Schema::hasColumn('services', 'is_long_term')) {
                $table->boolean('is_long_term')->default(false)->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'is_long_term']);
        });
    }
};

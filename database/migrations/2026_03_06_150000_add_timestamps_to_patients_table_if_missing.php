<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add created_at/updated_at to patients table if missing (legacy tables may not have them).
     */
    public function up(): void
    {
        if (!Schema::hasTable('patients')) {
            return;
        }
        if (!Schema::hasColumn('patients', 'created_at')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
            });
        }
        if (!Schema::hasColumn('patients', 'updated_at')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('patients')) {
            if (Schema::hasColumn('patients', 'created_at')) {
                Schema::table('patients', function (Blueprint $table) {
                    $table->dropColumn('created_at');
                });
            }
            if (Schema::hasColumn('patients', 'updated_at')) {
                Schema::table('patients', function (Blueprint $table) {
                    $table->dropColumn('updated_at');
                });
            }
        }
    }
};

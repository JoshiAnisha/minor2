<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bids')) {
            return;
        }
        if (Schema::hasColumn('bids', 'message')) {
            return;
        }
        Schema::table('bids', function (Blueprint $table) {
            $table->text('message')->nullable()->after('proposed_price');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('bids') && Schema::hasColumn('bids', 'message')) {
            Schema::table('bids', function (Blueprint $table) {
                $table->dropColumn('message');
            });
        }
    }
};

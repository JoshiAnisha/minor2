<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assigned_service_bids')) {
            return;
        }
        Schema::create('assigned_service_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_service_id')->constrained('assigned_services')->cascadeOnDelete();
            $table->foreignId('caregiver_id')->constrained('caregivers')->cascadeOnDelete();
            $table->decimal('proposed_price', 12, 2);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['assigned_service_id', 'caregiver_id'], 'as_bids_service_caregiver_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assigned_service_bids');
    }
};

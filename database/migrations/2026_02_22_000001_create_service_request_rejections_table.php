<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_request_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained('caregivers')->cascadeOnDelete();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['caregiver_id', 'service_request_id'], 'sr_rejections_caregiver_request_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_request_rejections');
    }
};

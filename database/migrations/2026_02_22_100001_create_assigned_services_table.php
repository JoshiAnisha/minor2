<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assigned_services')) {
            return;
        }
        Schema::create('assigned_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('preferred_date');
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('assigned_caregiver_id')->nullable();
            $table->enum('status', ['pending', 'accepted', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('assigned_caregiver_id')->references('id')->on('caregivers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assigned_services');
    }
};

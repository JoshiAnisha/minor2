<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('patients_id')->constrained()->cascadeOnDelete();
    $table->foreignId('caregivers_id')->constrained()->cascadeOnDelete();
    $table->foreignId('services_id')->constrained()->cascadeOnDelete();

    $table->dateTime('date_time')->nullable();
    $table->enum('status', ['pending', 'accepted', 'completed', 'cancelled']);
    $table->text('location');
    $table->decimal('price', 10, 2)->nullable();
    $table->enum('duration_type', ['one-time', 'weekly', 'monthly'])->default('one-time');
    $table->date('start_date');
    $table->date('end_date');
    $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
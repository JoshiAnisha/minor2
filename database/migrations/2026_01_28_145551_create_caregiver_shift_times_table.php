<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregiver_shift_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->enum('shift', ['Day', 'Night', 'Both']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('day');
            $table->string('service');
            $table->date('available_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_shift_times');
    }
};

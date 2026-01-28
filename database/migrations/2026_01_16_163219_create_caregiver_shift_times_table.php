<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('caregiver_shift_times', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('caregiver_id');

            $table->enum('shift', ['Day', 'Night', 'Both']);
            $table->time('start_time');
            $table->time('end_time');

            $table->string('day'); // Monday, Tuesday, etc.
            $table->string('service');

            $table->date('available_date');

            $table->timestamps();

            // Foreign key
            $table->foreign('caregiver_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_shift_times');
    }
};

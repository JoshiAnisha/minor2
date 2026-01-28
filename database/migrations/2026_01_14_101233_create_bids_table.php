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
        Schema::create('bids', function (Blueprint $table) {
            $table->id(); 
            $table->decimal('proposed_price', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected']);
            // $table->foreign('caregivers_id')->references('id')->on('caregivers')->onDelete('set null');
            $table->foreignId('caregivers_id')->constrained()->on('caregivers')->onDelete('cascade');
            $table->foreignId('bookings_id')->constrained()->on('caregivers')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
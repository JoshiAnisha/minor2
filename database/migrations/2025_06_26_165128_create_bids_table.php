<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->decimal('proposed_price', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->foreignId('caregivers_id')->constrained('caregivers')->onDelete('cascade');
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};

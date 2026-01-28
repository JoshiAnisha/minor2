
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('comments');
            $table->foreignId('user_id')->constrained()->on('users')->onDelete('cascade');
            $table->foreignId('bookings_id')->constrained()->on('caregivers')->onDelete('cascade');
            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

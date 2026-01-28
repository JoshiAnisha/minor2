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
        Schema::create('caregivers', function (Blueprint $table) {
         $table->id();
        $table->enum('caregiver_type', ['medical', 'regular'])->nullable();
        $table->text('qualification')->nullable();
        $table->text('experience')->nullable();
        $table->text('skills')->nullable();
        $table->string('license_number', 50)->nullable();
        $table->text('training_certificate')->nullable();
        $table->boolean('background_check_status')->nullable(); 
        $table->boolean('verified_status')->nullable();         
        $table->decimal('rating', 3, 2)->nullable();            
        $table->boolean('availability_status')->nullable();     
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('address')->nullable();
        $table->string('field')->nullable();
        $table->text('bio')->nullable();
        $table->string('certificate_path')->nullable();
        $table->timestamps();
        
       });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caregivers');
    }
};
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
        Schema::create('labtests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->date('scheduled_date');
            $table->enum('status',['scheduled','completed','cancelled']);
            $table->text('report');
            $table->foreignId('patients_id')->constrained()->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labtests');
    }
};

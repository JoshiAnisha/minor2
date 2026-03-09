<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add any missing columns to patients table so profile updates work.
     */
    public function up(): void
    {
        if (!Schema::hasTable('patients')) {
            return;
        }

        $addColumn = function (string $name, \Closure $definition) {
            if (!Schema::hasColumn('patients', $name)) {
                Schema::table('patients', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        };

        $addColumn('user_id', fn (Blueprint $t) => $t->unsignedBigInteger('user_id')->nullable());
        $addColumn('email', fn (Blueprint $t) => $t->string('email')->nullable());
        $addColumn('date_of_birth', fn (Blueprint $t) => $t->date('date_of_birth')->nullable());
        $addColumn('gender', fn (Blueprint $t) => $t->string('gender', 20)->nullable());
        $addColumn('blood_group', fn (Blueprint $t) => $t->string('blood_group', 10)->nullable());
        $addColumn('contact_number', fn (Blueprint $t) => $t->string('contact_number', 20)->nullable());
        $addColumn('address', fn (Blueprint $t) => $t->text('address')->nullable());
        $addColumn('city', fn (Blueprint $t) => $t->string('city')->nullable());
        $addColumn('state', fn (Blueprint $t) => $t->string('state')->nullable());
        $addColumn('postal_code', fn (Blueprint $t) => $t->string('postal_code', 20)->nullable());
        $addColumn('emergency_contact_name', fn (Blueprint $t) => $t->string('emergency_contact_name')->nullable());
        $addColumn('emergency_contact_number', fn (Blueprint $t) => $t->string('emergency_contact_number', 20)->nullable());
        $addColumn('insurance_provider', fn (Blueprint $t) => $t->string('insurance_provider')->nullable());
        $addColumn('insurance_number', fn (Blueprint $t) => $t->string('insurance_number')->nullable());
        $addColumn('medical_history', fn (Blueprint $t) => $t->text('medical_history')->nullable());
        $addColumn('prescriptions', fn (Blueprint $t) => $t->text('prescriptions')->nullable());
        $addColumn('health_condition', fn (Blueprint $t) => $t->text('health_condition')->nullable());
        $addColumn('allergies', fn (Blueprint $t) => $t->text('allergies')->nullable());
        $addColumn('disabilities', fn (Blueprint $t) => $t->text('disabilities')->nullable());
        $addColumn('verified_status', fn (Blueprint $t) => $t->boolean('verified_status')->default(false));
        $addColumn('rating', fn (Blueprint $t) => $t->decimal('rating', 3, 2)->nullable());
        $addColumn('notes', fn (Blueprint $t) => $t->text('notes')->nullable());
        $addColumn('profile_photo', fn (Blueprint $t) => $t->string('profile_photo')->nullable());
        if (!Schema::hasColumn('patients', 'created_at')) {
            Schema::table('patients', fn (Blueprint $t) => $t->timestamp('created_at')->nullable());
        }
        if (!Schema::hasColumn('patients', 'updated_at')) {
            Schema::table('patients', fn (Blueprint $t) => $t->timestamp('updated_at')->nullable());
        }
    }

    /**
     * Reverse: we don't drop columns in down() to avoid data loss.
     */
    public function down(): void
    {
        // No-op: safe rollback
    }
};

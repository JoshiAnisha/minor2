<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make patients columns nullable so profile updates don't fail when fields are empty.
     */
    public function up(): void
    {
        if (!Schema::hasTable('patients') || DB::getDriverName() !== 'mysql') {
            return;
        }

        $nullableColumns = [
            'medical_history'   => 'TEXT',
            'prescriptions'     => 'TEXT',
            'health_condition'  => 'TEXT',
            'allergies'         => 'TEXT',
            'disabilities'      => 'TEXT',
            'notes'             => 'TEXT',
            'address'           => 'TEXT',
            'email'             => 'VARCHAR(255)',
            'date_of_birth'     => 'DATE',
            'gender'            => 'VARCHAR(20)',
            'blood_group'       => 'VARCHAR(10)',
            'contact_number'    => 'VARCHAR(20)',
            'city'              => 'VARCHAR(255)',
            'state'             => 'VARCHAR(100)',
            'postal_code'       => 'VARCHAR(20)',
            'emergency_contact_name'   => 'VARCHAR(255)',
            'emergency_contact_number' => 'VARCHAR(20)',
            'insurance_provider' => 'VARCHAR(255)',
            'insurance_number'  => 'VARCHAR(255)',
            'profile_photo'     => 'VARCHAR(255)',
        ];

        foreach ($nullableColumns as $col => $type) {
            if (Schema::hasColumn('patients', $col)) {
                DB::statement("ALTER TABLE patients MODIFY COLUMN `{$col}` {$type} NULL");
            }
        }
    }

    public function down(): void
    {
        // No-op
    }
};

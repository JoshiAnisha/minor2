<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Caregiver;
use Illuminate\Database\Seeder;

class BackfillCaregiverPatientSeeder extends Seeder
{
    /**
     * Create Caregiver/Patient records for existing users who don't have them.
     */
    public function run(): void
    {
        foreach (User::where('role', 'caregiver')->get() as $user) {
            if (!$user->caregiver) {
                Caregiver::create([
                    'user_id' => $user->id,
                    'users_id' => $user->id,
                ]);
            }
        }

        foreach (User::where('role', 'patient')->get() as $user) {
            if (!$user->patient) {
                Patient::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
        }
    }
}

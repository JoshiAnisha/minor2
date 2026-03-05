<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class CustomServiceSeeder extends Seeder
{
    /**
     * Ensure "Other / Custom request" service exists for patients to request services not in the catalog.
     */
    public function run(): void
    {
        Service::firstOrCreate(
            ['slug' => 'other-custom-request'],
            [
                'name' => 'Other (describe in request)',
                'details' => 'Use this when the service you need is not in the list. Describe your need, preferred date, time, and location. Caregivers can respond with a bid.',
                'base_price' => 0,
                'service_type' => 'regular',
            ]
        );
    }
}

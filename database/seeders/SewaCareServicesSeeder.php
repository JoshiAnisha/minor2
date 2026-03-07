<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SewaCareServicesSeeder extends Seeder
{
    /**
     * Seed the 10 SewaCare services with categories and long-term flags.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Doctor Visit at Home',
                'slug' => 'doctor-visit-at-home',
                'details' => "A doctor visits the patient's home to:\n• Check symptoms\n• Diagnose illness\n• Prescribe medicines\n• Monitor recovery",
                'base_price' => 1500,
                'service_type' => 'medical',
                'category' => 'Doctor & Consultation',
                'is_long_term' => false,
            ],
            [
                'name' => 'Nursing Care',
                'slug' => 'nursing-care',
                'details' => "Professional nurses provide services like:\n• Giving injections\n• Wound dressing\n• IV drip management\n• Post-surgery care\n• Monitoring blood pressure, temperature, etc.",
                'base_price' => 1200,
                'service_type' => 'medical',
                'category' => 'Nursing & Care',
                'is_long_term' => true,
            ],
            [
                'name' => 'Physiotherapy',
                'slug' => 'physiotherapy',
                'details' => "A physiotherapist visits the home for:\n• Rehabilitation after injury or surgery\n• Joint and muscle therapy\n• Stroke recovery exercises",
                'base_price' => 1000,
                'service_type' => 'medical',
                'category' => 'Therapy',
                'is_long_term' => true,
            ],
            [
                'name' => 'Elderly Care / Caregiver Service',
                'slug' => 'elderly-care-caregiver-service',
                'details' => "Caregivers help elderly people with:\n• Daily activities (bathing, eating, walking)\n• Medicine reminders\n• Basic health monitoring",
                'base_price' => 800,
                'service_type' => 'regular',
                'category' => 'Elderly & Caregiver',
                'is_long_term' => true,
            ],
            [
                'name' => 'Laboratory Sample Collection',
                'slug' => 'laboratory-sample-collection',
                'details' => "Lab technicians come to the home to collect:\n• Blood samples\n• Urine samples\n• Other diagnostic samples",
                'base_price' => 300,
                'service_type' => 'medical',
                'category' => 'Lab & Pharmacy',
                'is_long_term' => false,
            ],
            [
                'name' => 'Medicine Delivery',
                'slug' => 'medicine-delivery',
                'details' => 'Pharmacies deliver prescribed medicines directly to the patient\'s home.',
                'base_price' => 150,
                'service_type' => 'medical',
                'category' => 'Lab & Pharmacy',
                'is_long_term' => false,
            ],
            [
                'name' => 'Telemedicine / Online Consultation',
                'slug' => 'telemedicine-online-consultation',
                'details' => 'Patients can consult doctors online using video or phone calls.',
                'base_price' => 500,
                'service_type' => 'medical',
                'category' => 'Doctor & Consultation',
                'is_long_term' => false,
            ],
            [
                'name' => 'Medical Equipment Rental',
                'slug' => 'medical-equipment-rental',
                'details' => "Patients can rent equipment like:\n• Oxygen cylinders\n• Wheelchairs\n• Hospital beds\n• Nebulizers",
                'base_price' => 500,
                'service_type' => 'medical',
                'category' => 'Equipment',
                'is_long_term' => true,
            ],
            [
                'name' => 'Palliative Care',
                'slug' => 'palliative-care',
                'details' => "Care for patients with serious illnesses focusing on:\n• Pain management\n• Comfort care\n• Emotional support",
                'base_price' => 2000,
                'service_type' => 'medical',
                'category' => 'Specialized Care',
                'is_long_term' => true,
            ],
            [
                'name' => 'Post-Hospital Care',
                'slug' => 'post-hospital-care',
                'details' => "After discharge from the hospital:\n• Nurses monitor recovery\n• Medication management\n• Dressing and rehabilitation",
                'base_price' => 1500,
                'service_type' => 'medical',
                'category' => 'Nursing & Care',
                'is_long_term' => true,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'category' => $data['category'] ?? null,
                    'is_long_term' => $data['is_long_term'] ?? false,
                ])
            );
        }
    }
}

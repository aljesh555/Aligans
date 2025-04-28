<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Social Media Settings
        Setting::set('social_links', [
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'youtube' => ''
        ], 'json', 'social', 'Social media links');

        // Customer Care Settings
        $customerCareSettings = [
            'customer_care_phone' => '+977-1-4444444',
            'customer_care_email' => 'care@yourstore.com',
            'customer_care_working_hours' => 'Sun-Fri: 9:00 AM - 6:00 PM',
            'customer_care_address' => 'Kathmandu, Nepal',
            'customer_care_whatsapp' => '+977-9800000000',
            'customer_care_viber' => '+977-9800000000'
        ];

        foreach ($customerCareSettings as $key => $value) {
            Setting::set(
                $key,
                $value,
                'string',
                'customer_care',
                'Customer care ' . str_replace('customer_care_', '', $key)
            );
        }
    }
} 
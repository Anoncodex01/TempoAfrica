<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'WiFi',
                'icon' => 'wifi',
                'description' => 'Free wireless internet access',
                'is_active' => true,
            ],
            [
                'name' => 'Parking',
                'icon' => 'local_parking',
                'description' => 'Free parking available',
                'is_active' => true,
            ],
            [
                'name' => 'Pool',
                'icon' => 'pool',
                'description' => 'Swimming pool access',
                'is_active' => true,
            ],
            [
                'name' => 'Gym',
                'icon' => 'fitness_center',
                'description' => 'Fitness center access',
                'is_active' => true,
            ],
            [
                'name' => 'Restaurant',
                'icon' => 'restaurant',
                'description' => 'On-site restaurant',
                'is_active' => true,
            ],
            [
                'name' => 'Bar',
                'icon' => 'local_bar',
                'description' => 'Bar and lounge',
                'is_active' => true,
            ],
            [
                'name' => 'Spa',
                'icon' => 'spa',
                'description' => 'Spa and wellness center',
                'is_active' => true,
            ],
            [
                'name' => 'Conference Room',
                'icon' => 'meeting_room',
                'description' => 'Business meeting facilities',
                'is_active' => true,
            ],
            [
                'name' => 'Air Conditioning',
                'icon' => 'ac_unit',
                'description' => 'Air conditioning in rooms',
                'is_active' => true,
            ],
            [
                'name' => 'Kitchen',
                'icon' => 'kitchen',
                'description' => 'Kitchen facilities available',
                'is_active' => true,
            ],
            [
                'name' => 'Balcony',
                'icon' => 'balcony',
                'description' => 'Private balcony',
                'is_active' => true,
            ],
            [
                'name' => 'Ocean View',
                'icon' => 'beach_access',
                'description' => 'Ocean or sea view',
                'is_active' => true,
            ],
            [
                'name' => 'Mountain View',
                'icon' => 'landscape',
                'description' => 'Mountain or scenic view',
                'is_active' => true,
            ],
            [
                'name' => 'City View',
                'icon' => 'location_city',
                'description' => 'City skyline view',
                'is_active' => true,
            ],
            [
                'name' => 'Pet Friendly',
                'icon' => 'pets',
                'description' => 'Pet-friendly accommodation',
                'is_active' => true,
            ],
            [
                'name' => 'Family Friendly',
                'icon' => 'family_restroom',
                'description' => 'Suitable for families',
                'is_active' => true,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
} 
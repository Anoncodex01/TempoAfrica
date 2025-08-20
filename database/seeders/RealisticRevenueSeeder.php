<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\HouseBooking;
use App\Models\Customer;
use App\Models\Accommodation;
use App\Models\House;
use Carbon\Carbon;

class RealisticRevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing customers, accommodations, and houses
        $customers = Customer::all();
        $accommodations = Accommodation::all();
        $houses = House::all();

        if ($customers->isEmpty() || $accommodations->isEmpty() || $houses->isEmpty()) {
            $this->command->info('Skipping realistic revenue seeder - need customers, accommodations, and houses first');
            return;
        }

        // Create realistic paid bookings for accommodations
        $accommodationAmounts = [
            25000, 35000, 45000, 55000, 65000, 75000, 85000, 95000, 120000, 150000,
            180000, 200000, 250000, 300000, 350000, 400000, 450000, 500000
        ];

        for ($i = 0; $i < 25; $i++) {
            $amount = $accommodationAmounts[array_rand($accommodationAmounts)];
            $createdAt = Carbon::now()->subDays(rand(1, 90));
            
            Booking::create([
                'is_paid' => true,
                'reference' => 'REAL-' . strtoupper(uniqid()) . '-' . strtoupper(substr(md5(rand()), 0, 4)),
                'currency' => 'TZS',
                'price' => $amount,
                'amount' => $amount,
                'amount_paid' => $amount,
                'paid_at' => $createdAt,
                'accommodation_id' => $accommodations->random()->id,
                'accommodation_room_id' => null,
                'customer_id' => $customers->random()->id,
                'is_checked_in' => rand(0, 1),
                'is_checked_out' => rand(0, 1),
                'is_cancelled' => false,
                'from_date' => $createdAt->copy()->addDays(rand(1, 30)),
                'to_date' => $createdAt->copy()->addDays(rand(2, 7)),
                'pacs' => rand(1, 4),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Create realistic paid house bookings
        $houseAmounts = [
            5000, 7500, 10000, 15000, 20000, 25000, 30000, 35000, 40000, 50000
        ];

        for ($i = 0; $i < 15; $i++) {
            $amount = $houseAmounts[array_rand($houseAmounts)];
            $createdAt = Carbon::now()->subDays(rand(1, 60));
            
            HouseBooking::create([
                'is_paid' => true,
                'reference' => 'HOUSE-' . strtoupper(uniqid()) . '-' . strtoupper(substr(md5(rand()), 0, 4)),
                'currency' => 'TZS',
                'price' => $amount,
                'amount' => $amount,
                'amount_paid' => $amount,
                'paid_at' => $createdAt,
                'house_id' => $houses->random()->id,
                'customer_id' => $customers->random()->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('✅ Added 25 realistic accommodation bookings and 15 house bookings with paid status');
    }
}

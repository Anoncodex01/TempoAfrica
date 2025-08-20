<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('streets')->truncate();
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        DB::table('countries')->truncate();

        // Insert countries
        DB::table('countries')->insert([
            [
                'id' => 1,
                'name' => 'Tanzania',
                'country_code' => 'TZ',
                'zip_code' => '255',
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert provinces
        DB::table('provinces')->insert([
            [
                'id' => 1,
                'name' => 'Dar es Salaam',
                'country_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Arusha',
                'country_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Mwanza',
                'country_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert districts
        DB::table('districts')->insert([
            [
                'id' => 1,
                'name' => 'Ilala',
                'province_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Kinondoni',
                'province_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Temeke',
                'province_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Ubungo',
                'province_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Kigamboni',
                'province_id' => 1,
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert streets
        DB::table('streets')->insert([
            [
                'id' => 1,
                'name' => 'Samora Avenue',
                'province_id' => 1,
                'district_id' => 1, // Ilala
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Ohio Street',
                'province_id' => 1,
                'district_id' => 1, // Ilala
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Maktaba Street',
                'province_id' => 1,
                'district_id' => 1, // Ilala
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Independence Avenue',
                'province_id' => 1,
                'district_id' => 1, // Ilala
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Mbezi Beach Road',
                'province_id' => 1,
                'district_id' => 2, // Kinondoni
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Masaki Road',
                'province_id' => 1,
                'district_id' => 2, // Kinondoni
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Temeke Road',
                'province_id' => 1,
                'district_id' => 3, // Temeke
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Ubungo Road',
                'province_id' => 1,
                'district_id' => 4, // Ubungo
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Kigamboni Road',
                'province_id' => 1,
                'district_id' => 5, // Kigamboni
                'is_established' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 
<?php

namespace Database\Seeders;

use App\Models\CityCorporation;
use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityCorporationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dhakaDistrict = District::where('name', 'Dhaka')->first();

        if (!$dhakaDistrict) return;

        $corps = [
            'Dhaka North City Corporation (DNCC)',
            'Dhaka South City Corporation (DSCC)',
        ];

        foreach ($corps as $name) {
            CityCorporation::firstOrCreate([
                'district_id' => $dhakaDistrict->id,
                'name' => $name,
            ]);
        }
    }
}

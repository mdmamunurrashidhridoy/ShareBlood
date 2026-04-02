<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'Barisal' => [
                'Barguna',
                'Barishal',
                'Bhola',
                'Jhalokati',
                'Patuakhali',
                'Pirojpur'
            ],
            'Chattogram' => [
                'Bandarban',
                'Brahmanbaria',
                'Chandpur',
                'Chattogram',
                'Cumilla',
                "Cox's Bazar",
                'Feni',
                'Khagrachhari',
                'Lakshmipur',
                'Noakhali',
                'Rangamati'
            ],
            'Dhaka' => [
                'Dhaka',
                'Faridpur',
                'Gazipur',
                'Gopalganj',
                'Kishoreganj',
                'Madaripur',
                'Manikganj',
                'Munshiganj',
                'Narayanganj',
                'Narsingdi',
                'Rajbari',
                'Shariatpur',
                'Tangail'
            ],
            'Khulna' => [
                'Bagerhat',
                'Chuadanga',
                'Jashore',
                'Jhenaidah',
                'Khulna',
                'Kushtia',
                'Magura',
                'Meherpur',
                'Narail',
                'Satkhira'
            ],
            'Mymensingh' => [
                'Jamalpur',
                'Mymensingh',
                'Netrokona',
                'Sherpur'
            ],
            'Rajshahi' => [
                'Bogura',
                'Chapainawabganj',
                'Joypurhat',
                'Naogaon',
                'Natore',
                'Pabna',
                'Rajshahi',
                'Sirajganj'
            ],
            'Rangpur' => [
                'Dinajpur',
                'Gaibandha',
                'Kurigram',
                'Lalmonirhat',
                'Nilphamari',
                'Panchagarh',
                'Rangpur',
                'Thakurgaon'
            ],
            'Sylhet' => [
                'Habiganj',
                'Moulvibazar',
                'Sunamganj',
                'Sylhet'
            ],
        ];

        foreach ($districts as $divisionName => $districtList) {

            $division = Division::where('name', $divisionName)->first();

            if (!$division) continue;

            foreach ($districtList as $district) {
                District::firstOrCreate(
                    ['division_id' => $division->id, 'name' => $district],
                    ['division_id' => $division->id, 'name' => $district]
                );
            }
        }
    }
}

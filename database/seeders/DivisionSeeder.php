<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            'Dhaka',
            'Chattogram',
            'Rajshahi',
            'Khulna',
            'Barishal',
            'Sylhet',
            'Rangpur',
            'Mymensingh',
        ];

        foreach ($divisions as $division) {
            Division::create([
                'name' => $division,
            ]);
        }
        
    }
}

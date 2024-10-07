<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesiredGenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desiredGenders = [
            ['name' => 'Male'],
            ['name' => 'Female'],
            ['name' => 'Non-binary'],
            ['name' => 'Other'],
            ['name' => 'Prefer not to say'],
        ];

        // Insert genders into the database
        foreach ($desiredGenders as $desiredGender) {
            \App\Models\DesiredGender::create($desiredGender);
        }
    }
}

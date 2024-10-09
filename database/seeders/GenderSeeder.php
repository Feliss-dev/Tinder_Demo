<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $genders = [
            ['name' => 'Male'],
            ['name' => 'Female'],
            ['name' => 'Non-binary'],
            ['name' => 'Other'],
            ['name' => 'Prefer not to say'],
        ];

        // Insert genders into the database
        foreach ($genders as $gender) {
            \App\Models\Gender::create($gender);
        }
    }
}

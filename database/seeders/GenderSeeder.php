<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gender::factory()->createMany([
            ['name' => 'Male'],
            ['name' => 'Female'],
            ['name' => 'Non-binary'],
            ['name' => 'Other'],
            ['name' => 'Prefer not to say'],
        ]);
    }
}

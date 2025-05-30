<?php

namespace Database\Seeders;

use App\Models\DesiredGender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesiredGenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DesiredGender::factory()->createMany([
            ['name' => 'Male'],
            ['name' => 'Female'],
            ['name' => 'Non-binary'],
            ['name' => 'Other'],
            ['name' => 'Prefer not to say'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interest::factory()->createMany([
            ['name' => 'Sports'],
            ['name' => 'Music'],
            ['name' => 'Travel'],
            ['name' => 'Cooking'],
            ['name' => 'Reading'],
            ['name' => 'Technology'],
            ['name' => 'Art'],
            ['name' => 'Movies'],
            ['name' => 'Gaming'],
            ['name' => 'Fitness'],
        ]);
    }
}

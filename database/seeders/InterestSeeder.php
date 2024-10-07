<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
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
        ];

        // Insert interests into the database
        foreach ($interests as $interest) {
            \App\Models\Interest::create($interest);
        }
    }
}

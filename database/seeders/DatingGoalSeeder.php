<?php

namespace Database\Seeders;

use App\Models\DatingGoal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatingGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DatingGoal::factory()->createMany([
            ['name' => 'Casual Dating'],
            ['name' => 'Serious Relationship'],
            ['name' => 'Friendship'],
            ['name' => 'Networking'],
            ['name' => 'Marriage'],
            ['name' => 'Other'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatingGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datingGoals = [
            ['name' => 'Casual Dating'],
            ['name' => 'Serious Relationship'],
            ['name' => 'Friendship'],
            ['name' => 'Networking'],
            ['name' => 'Marriage'],
            ['name' => 'Other'],
        ];

        // Insert dating goals into the database
        foreach ($datingGoals as $datingGoal) {
            \App\Models\DatingGoal::create($datingGoal);
        }
    }
}

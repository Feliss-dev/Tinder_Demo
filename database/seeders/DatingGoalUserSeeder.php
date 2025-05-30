<?php

namespace Database\Seeders;

use App\Models\DatingGoal;
use App\Models\DatingGoalUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatingGoalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $goals = DatingGoal::all();

        DatingGoalUser::factory()->count($users->count())->state(new Sequence(function (Sequence $sequence) use ($users, $goals) {
            return [
                'user_id' => $users[$sequence->index]->id,
                'dating_goal_id' => $goals->random()->id,
            ];
        }))->create();
    }
}

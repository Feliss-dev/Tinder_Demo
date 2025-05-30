<?php

namespace Database\Seeders;

use App\Models\DesiredGender;
use App\Models\DesiredGenderUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DesiredGenderUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $genders = DesiredGender::all();

        DesiredGenderUser::factory()->count($users->count())->state(new Sequence(function (Sequence $sequence) use ($users, $genders) {
            return [
                'user_id' => $users[$sequence->index]->id,
                'desired_gender_id' => $genders->random()->id,
            ];
        }))->create();
    }
}

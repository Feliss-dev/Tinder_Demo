<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\GenderUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class GenderUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $genders = Gender::all();

        GenderUser::factory()->count($users->count())->state(new Sequence(function (Sequence $sequence) use ($users, $genders) {
            return [
                'user_id' => $users[$sequence->index]->id,
                'gender_id' => $genders->random()->id,
            ];
        }))->create();
    }
}

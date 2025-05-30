<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\InterestUser;
use App\Models\Language;
use App\Models\LanguageUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class InterestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $interests = Interest::all();

        $list = [];

        foreach ($users as $user) {
            foreach ($interests->random(rand(1, 3)) as $interest) {
                $list[] = [
                    'user_id' => $user->id,
                    'interest_id' => $interest->id,
                ];
            }
        }

        InterestUser::factory()->count(count($list))->state(new Sequence(function (Sequence $sequence) use ($list) {
            return [
                'user_id' => $list[$sequence->index]['user_id'],
                'interest_id' => $list[$sequence->index]['interest_id'],
            ];
        }))->create();
    }
}

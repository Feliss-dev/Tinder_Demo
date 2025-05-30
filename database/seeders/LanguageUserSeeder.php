<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\GenderUser;
use App\Models\Language;
use App\Models\LanguageUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class LanguageUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $languages = Language::all();

        $list = [];

        foreach ($users as $user) {
            foreach ($languages->random(rand(1, 3)) as $language) {
                $list[] = [
                    'user_id' => $user->id,
                    'language_id' => $language->id,
                ];
            }
        }

        LanguageUser::factory()->count(count($list))->state(new Sequence(function (Sequence $sequence) use ($list) {
            return [
                'user_id' => $list[$sequence->index]['user_id'],
                'language_id' => $list[$sequence->index]['language_id'],
            ];
        }))->create();
    }
}

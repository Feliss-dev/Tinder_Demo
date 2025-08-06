<?php

namespace Database\Seeders;

use App\Models\ApplicationLanguage;
use App\Models\User;
use App\Models\UserPreference;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userImageFiles = collect(Storage::disk("public")->allFiles('user_images'));

        // Seed fake users.
        $users = User::factory(100)->sequence(function (Sequence $sequence) use ($userImageFiles) {
            return [
                'birth_date' => Carbon::createFromTimestamp(rand(Carbon::now()->subYears(60)->timestamp, Carbon::now()->subYears(20)->timestamp)),
                'created_at' => Carbon::createFromTimestamp(rand(Carbon::now()->subMonths(2)->timestamp, Carbon::now()->subMonths(50)->timestamp)),
                'images' => json_encode($userImageFiles->random(rand(3, 8))),
            ];
        })->create([
            'is_fake' => true,
            'is_admin' => false,
        ]);

        UserPreference::factory($users->count())->sequence(function (Sequence $sequence) use ($users) {
            return [
                'user_id' => $users[$sequence->index]->id,
            ];
        })->create([
            'language_id' => ApplicationLanguage::where('code', 'en')->pluck('id')->first(),
        ]);
    }
}

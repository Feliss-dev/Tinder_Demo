<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Interest;
use App\Models\User;
use App\Models\Swipe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        // Run these seeder classes.
        $this->call([
            GenderSeeder::class,
            DatingGoalSeeder::class,
            LanguageSeeder::class,
            InterestSeeder::class,
            DesiredGenderSeeder::class,
        ]);

        // Create fake users.
        $users = User::factory(50)->state(new Sequence(fn (Sequence $sequence) => [
            'birth_date' => Carbon::createFromTimestamp(rand(Carbon::now()->subYears(60)->timestamp, Carbon::now()->subYears(20)->timestamp))
        ]))->create([
            'is_fake' => true,
            'is_admin' => false,
        ]);

        // Create test user and admin usr.
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        $testAdmin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        foreach ($users as $user) {
            // Gán Gender (vào bảng pivot gender_user)
            $gender = \App\Models\Gender::inRandomOrder()->first()->id;
            $user->genders()->sync([$gender]);

            // Gán DatingGoal (vào bảng pivot dating_goal_user)
            $datingGoal = \App\Models\DatingGoal::inRandomOrder()->first()->id;
            $user->datingGoals()->sync([$datingGoal]);

            // Gán DesiredGender (vào bảng pivot desired_gender_user)
            $desiredGender = \App\Models\DesiredGender::inRandomOrder()->first()->id;
            $user->desiredGenders()->sync([$desiredGender]);

            // Gán Languages (nhiều ngôn ngữ vào bảng pivot language_user)
            $languages = \App\Models\Language::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $user->languages()->sync($languages);

            // Gán Interests (nhiều sở thích vào bảng pivot interest_user)
            $interests = \App\Models\Interest::inRandomOrder()->take(rand(1, 5))->pluck('id');
            $user->interests()->sync($interests);

            // Tạo một số Swipe cho mỗi user
            Swipe::factory()->create(['user_id' => $user->id, 'swiped_user_id' => $testUser->id]);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Interest;
use App\Models\User;
use App\Models\Swipe;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   


        public function run(): void
{
    // Seed data cho các bảng liên quan
    $this->call([
        GenderSeeder::class,
        DatingGoalSeeder::class,
        LanguageSeeder::class,
        InterestSeeder::class,
        DesiredGenderSeeder::class,
    ]);

    // Tạo người dùng giả với thông tin đầy đủ
    $users = User::factory(200)->create([
        'is_fake' => true,
        'is_admin' => false,
    ]);

    // Seed người dùng test và admin test
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

    // Gán các thuộc tính khác cho từng người dùng sau khi tạo
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

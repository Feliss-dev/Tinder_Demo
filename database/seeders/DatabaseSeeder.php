<?php

namespace Database\Seeders;

use App\Models\ApplicationLanguage;
use App\Models\User;
use App\Models\UserPreferences;
use Database\Factories\UserPreferencesFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run these seeder classes.
        $this->call([
            ApplicationLanguageSeeder::class,
            GenderSeeder::class,
            DatingGoalSeeder::class,
            LanguageSeeder::class,
            InterestSeeder::class,
            DesiredGenderSeeder::class,
            MessageReportReasonSeeder::class,

            AvatarImageSeeder::class,
            UserImageSeeder::class,
            UserSeeder::class,
            AvatarSeeder::class,
        ]);

        // Create test user and admin usr.
        $users = User::factory()->createMany([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'is_admin' => false,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'is_admin' => true,
            ]
        ]);

        UserPreferences::factory($users->count())->sequence(function (Sequence $sequence) use ($users) {
            return [
                'user_id' => $users[$sequence->index]->id,
            ];
        })->create([
            'language_id' => ApplicationLanguage::where('code', 'en')->pluck('id')->first(),
        ]);

        $banUser = User::factory()->create([
            'name' => 'Test Banned User',
            'email' => 'test-banned@example.com',
            'is_admin' => false,
        ]);

        UserPreferences::factory()->create([
            'user_id' => $banUser->id,
            'language_id' => ApplicationLanguage::where('code', 'en')->pluck('id')->first(),
        ]);

        $banUser->ban();

        $this->call([
            GenderUserSeeder::class,
            DesiredGenderUserSeeder::class,
            DatingGoalUserSeeder::class,
            LanguageUserSeeder::class,
            InterestUserSeeder::class,
        ]);

        $this->call(SwipeSeeder::class, false, [
            'testUser' => $users[0],
        ]);

        $this->call([
            MessageSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Faker\PicsumPhotoProvider;
use App\Faker\RandomUserProvider;
use App\Models\Avatar;
use App\Models\Conversation;
use App\Models\Interest;
use App\Models\SwipeMatch;
use App\Models\User;
use App\Models\Swipe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Illuminate\Filesystem\join_paths;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run these seeder classes.
        $this->call([
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
        $testUser = User::factory()->createMany([
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
        ])[0];

        $this->call([
            GenderUserSeeder::class,
            DesiredGenderUserSeeder::class,
            DatingGoalUserSeeder::class,
            LanguageUserSeeder::class,
            InterestUserSeeder::class,
        ]);

        $this->call(SwipeSeeder::class, false, ['testUser' => $testUser]);

        $this->call([
            MessageSeeder::class,
        ]);
    }
}

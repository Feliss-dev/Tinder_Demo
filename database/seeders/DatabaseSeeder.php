<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Faker\PicsumPhotoProvider;
use App\Faker\RandomUserProvider;
use App\Models\Avatar;
use App\Models\Interest;
use App\Models\User;
use App\Models\Swipe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Illuminate\Filesystem\join_paths;

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

        $faker = \Faker\Factory::create();
        $faker->addProvider(new RandomUserProvider($faker));
        $faker->addProvider(new PicsumPhotoProvider($faker));

        // Create fake users.
        $users = User::factory(20)->state(new Sequence(function (Sequence $sequence) use ($faker) {
            $images = [];

            for ($i = 0; $i < 3; $i++) {
                try {
                    $content = file_get_contents($faker->picsumUrl(500, 300));
                    $storagePath = 'user_images/' . ((string)Str::uuid()) . '.jpg';

                    Storage::disk("public")->put($storagePath, $content);
                    $images[] = $storagePath;
                } catch (\ErrorException) {}
            }

            return [
                'birth_date' => Carbon::createFromTimestamp(rand(Carbon::now()->subYears(60)->timestamp, Carbon::now()->subYears(20)->timestamp)),
                'created_at' => Carbon::createFromTimestamp(rand(Carbon::now()->subMonths(2)->timestamp, Carbon::now()->subMonths(50)->timestamp)),
                'images' => json_encode($images),
            ];
        }))->create([
            'is_fake' => true,
            'is_admin' => false,
        ]);

        // Assign fake avatars.
        Avatar::factory(User::count())->state(new Sequence(
            function (Sequence $sequence) use ($faker) {
                $storagePath = 'avatar/' . ($sequence->index + 1) . '_' . time() . '.jpg';
                $content = file_get_contents($faker->randomUserUrl("women"));

                Storage::disk("public")->put($storagePath, $content);

                return [
                    'user_id' => $sequence->index + 1,
                    'path' => $storagePath,
                ];
            }
        ))->create([
            'is_active' => 1,
        ]);

        // Create test user and admin usr.
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        User::factory()->create([
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

            $unswiped = User::whereNotIn('id', Swipe::where('swiped_user_id', $user->id)->pluck('swiped_user_id'))->inRandomOrder()->take(rand(1, 10))->pluck('id');
            Swipe::factory($unswiped->count())->state(new Sequence(function (Sequence $sequence) use ($unswiped) {
                return [
                    'swiped_user_id' => $unswiped[$sequence->index],
                ];
            }))->create([
                'user_id' => $user->id,
            ]);

            // Gán swipe với test user
            Swipe::factory()->create([
                'user_id' => $user->id,
                'swiped_user_id' => $testUser->id
            ]);
        }
    }
}

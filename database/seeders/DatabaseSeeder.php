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


         $users = User::factory(200)->create([
            'is_fake' =>true,
            'is_admin' => false,
         ]);

        //  \App\Models\Swipe::factory(5)->create();

        //  \App\Models\SwipeMatch::factory(2)->create();

        //  \App\Models\Conversation::factory(2)->create();

        //  \App\Models\Message::factory(5)->create();


        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        $testAdmin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $this->call([
            GenderSeeder::class,
            DatingGoalSeeder::class,
            LanguageSeeder::class,
            InterestSeeder::class,
            DesiredGenderSeeder::class,
        ]);

        #create swipes for test user

        foreach ($users as $user){
            Swipe::factory()->create(['user_id'=>$user->id, 'swiped_user_id'=>$testUser->id]);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


         \App\Models\User::factory(5)->create();

        //  \App\Models\Swipe::factory(5)->create();

        //  \App\Models\SwipeMatch::factory(2)->create();

        //  \App\Models\Conversation::factory(2)->create();

        //  \App\Models\Message::factory(5)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

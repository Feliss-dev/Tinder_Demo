<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Swipe;
use App\Models\SwipeMatch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SwipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(User $testUser): void
    {
        // Seeding to target.
        $users = User::where('is_fake', true)->where('is_admin', false)->where('id', '!=', $testUser->id)->get();

        Swipe::factory()->count($users->count())->sequence(function ($sequence) use ($users, $testUser) {
            return [
                'user_id' => $users[$sequence->index]->id,
                'swiped_user_id' => $testUser->id,
            ];
        })->create();

        // Make target seed with some fake users.
        $candidates = $users->random(rand((int)($users->count() * 0.15), (int)($users->count() * 0.3)));
        $swipes = Swipe::factory()->count($candidates->count())->sequence(function ($sequence) use ($candidates, $testUser) {
            return [
                'user_id' => $testUser->id,
                'swiped_user_id' => $candidates[$sequence->index]->id,
            ];
        })->create();

        // ...and create match from them.
        $matches = SwipeMatch::factory()->count($candidates->count())->sequence(function ($sequence) use ($candidates, $testUser, $swipes) {
            return [
                'swipe_id_1' => Swipe::where('user_id', $candidates[$sequence->index]->id)->where('swiped_user_id', $testUser->id)->first()->id,
                'swipe_id_2' => $swipes[$sequence->index]->id,
                'user_id_1' => min($candidates[$sequence->index]->id, $testUser->id),
                'user_id_2' => max($candidates[$sequence->index]->id, $testUser->id),
            ];
        })->create();

        // ...and create conversation from them.
        Conversation::factory()->count($matches->count())->sequence(function ($sequence) use ($matches) {
            $match = $matches[$sequence->index];

            return [
                'sender_id' => $match->user_id_1,
                'receiver_id' => $match->user_id_2,
                'match_id' => $match->id,
            ];
        })->create();
    }
}

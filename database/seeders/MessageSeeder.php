<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = Conversation::all();

        foreach ($conversations as $conversation) {
            Message::factory()->count(rand(8, 20))->sequence(function ($sequence) use ($conversation) {
                $sender = rand(0, 1);

                return [
                    'body' => fake()->text(50),
                    'sender_id' => $sender == 0 ? $conversation->sender_id : $conversation->receiver_id,
                    'receiver_id' => $sender == 0 ? $conversation->receiver_id : $conversation->sender_id,
                ];
            })->create([
                'conversation_id' => $conversation->id,
                'files' => '[]'
            ]);
        }

        // Conversation::factory()->count();
    }
}

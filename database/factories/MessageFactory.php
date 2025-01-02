<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Message::class;
    public function definition(): array
    {
        return [
            //
            'conversation_id' => Conversation::factory(),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'body' => $this->faker->sentence(),
            'read_at' => null,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\SwipeMatch;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    protected $model = Conversation::class;
    public function definition(): array
    {
        return [
            //
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'match_id' => SwipeMatch::factory(),
        ];
    }
}

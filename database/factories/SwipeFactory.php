<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Swipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Swipe>
 */
class SwipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Swipe::class;
    public function definition(): array
    {
        return [
            //
            'user_id' => User::factory(),
            'swiped_user_id' => User::factory(),
            'type' => $this->faker->randomElement(['left', 'right', 'up']),
        ];
    }
}

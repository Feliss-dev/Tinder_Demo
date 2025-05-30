<?php

namespace Database\Factories;

use App\Models\Swipe;
use App\Models\SwipeMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SwipeMatch>
 */
class SwipeMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SwipeMatch::class;
    public function definition(): array
    {
        return [

        ];
    }
}

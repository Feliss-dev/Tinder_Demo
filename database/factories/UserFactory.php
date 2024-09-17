<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\RelationshipGoalsEnum;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
           'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // or use Hash::make('password') if you prefer
            'remember_token' => Str::random(10),
            'birth_date' => $this->faker->optional()->date(),
            'gender' => $this->faker->optional()->randomElement(['male', 'female']),
            'bio' => $this->faker->optional()->text(),
            'interests' => $this->faker->optional()->words(3, true),
            'desired_gender' => $this->faker->optional()->randomElement(['male', 'female', 'any']),
            'dating_goal' => $this->faker->optional()->words(3, true),
            'images' => $this->faker->optional()->imageUrl(),
            'email_verified_at' => $this->faker->optional()->dateTime(),
            'is_admin' => $this->faker->boolean(20), // 20% chance of being true
         ];
    }

    // Congigure the model


    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

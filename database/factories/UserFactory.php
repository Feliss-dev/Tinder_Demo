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
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // mật khẩu mặc định
            'remember_token' => Str::random(10),
            'birth_date' => $this->faker->date('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'bio' => $this->faker->text(),
            'interests' => implode(',', $this->faker->words(3)), // Ví dụ: 'music, sports, travel'
            'desired_gender' => $this->faker->randomElement(['male', 'female']),
            'dating_goal' => $this->faker->sentence(),
            'images' => 'https://example.com/image.jpg', // Link ảnh giả
            'is_fake' => false,
            'is_admin' => false,
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

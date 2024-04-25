<?php

namespace Database\Factories;

use App\Models\UserSocial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSocial>
 */
class UserSocialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_name' => fake()->unique()->userName(),
            'provider_id' => fake()->unique()->randomNumber(5),
            'nickname' => fake()->unique()->userName(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\UserPhoneVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserPhoneVerification>
 */
class UserPhoneVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->e164PhoneNumber(),
            'code' => fake()->regexify('[a-zA-Z0-9]{4}'),
            'verified_at' => null,
            'expired_at' => fake()->dateTimeInInterval('now'),
        ];
    }

    public function notVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => $verifiedAt = fake()->dateTime(),
            'expired_at' => fake()->dateTimeInInterval($verifiedAt),
        ]);
    }
}

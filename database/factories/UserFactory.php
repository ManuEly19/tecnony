<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'personal_phone' => '09' . $this->faker->randomNumber(8),
            'address' => $this->faker->streetAddress,
            'password' => Hash::make('happysad'),

            'cedula' => '17' . $this->faker->randomNumber(8),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->name(),

            'home_phone' => '02' . $this->faker->randomNumber(7),
            'birthdate' => $this->faker->dateTimeBetween('-50 years', 'now'),
            'email_verified_at' => now(),

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

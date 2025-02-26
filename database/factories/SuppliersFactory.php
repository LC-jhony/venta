<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suppliers>
 */
class SuppliersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->company,
            'email'       => $this->faker->unique()->safeEmail,
            'phone'       => $this->faker->phoneNumber,
            'address'     => $this->faker->address,
            'status'      => $this->faker->randomElement(['1', '0']),
            'description' => $this->faker->sentence,
            'country'     => $this->faker->country,
            'city'        => $this->faker->city,
            'state'       => $this->faker->state,
        ];
    }
}

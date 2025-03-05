<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashRegister>
 */
class CashRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'open_date' => $this->faker->dateTimeBetween('-2 years', now()),
            'close_date' => $this->faker->dateTimeBetween('-2 years', now()),
            'initial_amount' => $this->faker->randomFloat(2, 10, 1000),
            'final_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->boolean(),
        ];
    }
}

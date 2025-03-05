<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
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
            'number_quote'  => $this->faker->ean13(),
            'notes' => $this->faker->paragraph(),
            'valid_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status'=> $this->faker->boolean(),
            'total' => fake()->randomFloat(2, 100, 1000),
        ];
    }
}

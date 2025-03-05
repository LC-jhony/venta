<?php

namespace Database\Factories;

use App\Models\CashRegister;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashMovement>
 */
class CashMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cash_register_id' => CashRegister::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(['Entrada', 'Salida']),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph(),
        ];
    }
}

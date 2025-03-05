<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
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
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'invoice_number' => $this->faker->ean13(),
            'subtotal' => $this->faker->randomFloat(2, 10, 1000),
            'tax' => $this->faker->randomFloat(2, 10, 1000),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'notes' => $this->faker->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-2 years', now()),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Quote;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
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
            'supplier_id' =>  Supplier::inRandomOrder()->first()->id,
            'quote_id' => Quote::inRandomOrder()->first()->id,
            'purchase_number' => $this->faker->ean13(),
            'status' => $this->faker->boolean(),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'created_at' => $this->faker->dateTimeBetween('-2 years', now()),

        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuoteProduct>
 */
class QuoteProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote_id' => Quote::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price_unit' => $this->faker->randomFloat(2, 10, 1000),
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}

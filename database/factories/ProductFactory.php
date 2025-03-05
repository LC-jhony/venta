<?php

namespace Database\Factories;

use App\Enum\TypeMeasure;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bar_code' => $this->faker->ean13(),
            'image' => $this->faker->imageUrl(),
            'name' => fake()->name(5, true),
            'purchase_price' => $this->faker->randomFloat(2, 10, 1000),
            'sales_price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 1000),
            'stock_minimum' => $this->faker->numberBetween(1, 100),
            'unit_measure' => $this->faker->randomElement(TypeMeasure::cases()),
            'category_id' => Category::inRandomOrder()->first()->id,
            'status' => $this->faker->boolean(),
            'expiration' => $this->faker->dateTimeBetween('now', '+2 years'),

        ];
    }
}

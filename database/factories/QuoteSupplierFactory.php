<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuoteSupplier>
 */
class QuoteSupplierFactory extends Factory
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
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
        ];
    }
}

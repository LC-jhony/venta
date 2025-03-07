<?php

namespace Database\Seeders;

use App\Models\QuoteProduct;
use Illuminate\Database\Seeder;

class QuoteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuoteProduct::factory(500)->create();
    }
}

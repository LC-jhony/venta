<?php

namespace Database\Seeders;

use App\Models\QuoteSupplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuoteSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuoteSupplier::factory(100)->create();
    }
}

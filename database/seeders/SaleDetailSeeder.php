<?php

namespace Database\Seeders;

use App\Models\SaleDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleDetail::factory(200)->create();
    }
}

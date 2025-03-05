<?php

namespace Database\Seeders;

use App\Models\CashMovement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CashMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashMovement::factory(500)->create();
    }
}

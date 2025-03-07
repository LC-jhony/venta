<?php

namespace Database\Seeders;

use App\Models\CashMovement;
use Illuminate\Database\Seeder;

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

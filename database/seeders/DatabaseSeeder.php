<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(2)->create();

       User::factory()->create([
              'name' => 'Test Admin',
              'email' => 'test@example.com',
              'password' => Hash::make('password'),
          ]);
     

         $this->call([
             CategorySeeder::class,
             ProductSeeder::class,
             CustomerSeeder::class,
             SuppliersSeeder::class,
             CashRegisterSeeder::class,
             CashMovementSeeder::class,
             SaleSeeder::class,
             SaleDetailSeeder::class,
             QuoteSeeder::class,
             QuoteProductSeeder::class,
             QuoteSupplierSeeder::class,
             PurchaseSeeder::class,
             PurchaseDetailSeeder::class,
         
         ]);
    }
}

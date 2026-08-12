<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AdminMenuSeeder::class,
            WarehouseSeeder::class,
            StockSeeder::class,
            SupplierSeeder::class,
            CategorySeeder::class,
            CustomerSeeder::class,
            CompanySeeder::class,
            CompanyDetailSeeder::class,
            OrderSeeder::class,
        ]);
    }
}



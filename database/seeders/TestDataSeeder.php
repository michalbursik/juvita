<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\WarehouseType;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Warehouses
        Warehouse::create([
            'id' => TestConstants::WAREHOUSE_MAIN_ID,
            'name' => 'Main Warehouse',
            'type' => WarehouseType::MAIN,
        ]);

        Warehouse::create([
            'id' => TestConstants::WAREHOUSE_TRASH_ID,
            'name' => 'Trash Warehouse',
            'type' => WarehouseType::TRASH,
        ]);

        // 2. Users
        User::create([
            'id' => TestConstants::USER_ADMIN_ID,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
            'warehouse_id' => TestConstants::WAREHOUSE_MAIN_ID,
        ]);

        User::create([
            'id' => TestConstants::USER_EMPLOYEE_ID,
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::EMPLOYEE->value,
            'warehouse_id' => TestConstants::WAREHOUSE_MAIN_ID,
        ]);

        // 3. Products
        $apple = Product::create([
            'id' => TestConstants::PRODUCT_APPLE_ID,
            'name' => 'Apple',
            'active' => true,
            'order' => 10,
            'unit' => 'kg',
        ]);

        $banana = Product::create([
            'id' => TestConstants::PRODUCT_BANANA_ID,
            'name' => 'Banana',
            'active' => true,
            'order' => 20,
            'unit' => 'kg',
        ]);

        // 4. Attach products to warehouses
        Warehouse::all()->each(function (Warehouse $warehouse) use ($apple, $banana) {
            $warehouse->products()->attach($apple->id, ['amount' => 0, 'price' => 0]);
            $warehouse->products()->attach($banana->id, ['amount' => 0, 'price' => 0]);
        });
    }
}

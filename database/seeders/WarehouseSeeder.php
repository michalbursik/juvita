<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            ['name' => 'Uherský Brod - Králov', 'type' => Warehouse::TYPE_MAIN],
            ['name' => 'Vozidlo 1', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Vozidlo 2', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Vozidlo 3', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Vozidlo 4', 'type' => Warehouse::TYPE_TEMPORARY],
        ];

        $products = Product::all();

        $pivotAttributes = [];
        foreach ($products as $key => $product) {
            $pivotAttributes[$key] = [
                'amount' => 0,
                'price' => 0.00,
            ];
        }

        foreach ($warehouses as $warehouseData) {
            $warehouse = new Warehouse($warehouseData);

            $warehouse->save();

            $warehouse->products()->saveMany($products, $pivotAttributes);
        }
    }
}

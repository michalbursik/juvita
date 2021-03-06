<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private WarehouseRepository $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            ['name' => 'Uherský Brod - Králov', 'type' => Warehouse::TYPE_MAIN],
            ['name' => 'Vozidlo Jaroslav', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Vozidlo Honza', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Vozidlo Tomáš', 'type' => Warehouse::TYPE_TEMPORARY],
            ['name' => 'Prodej', 'type' => Warehouse::TYPE_INTERNAL],
            ['name' => 'Kompost/Odpad ', 'type' => Warehouse::TYPE_TRASH],
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
            $warehouse = $this->warehouseRepository->store($warehouseData, $products, $pivotAttributes);
        }
    }
}

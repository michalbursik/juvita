<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
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

        foreach ($warehouses as $warehouseData) {
            $warehouse = $this->warehouseService->createWarehouse($warehouseData);
        }
    }
}

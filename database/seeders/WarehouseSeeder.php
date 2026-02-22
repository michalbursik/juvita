<?php

namespace Database\Seeders;

use App\Enums\WarehouseType;
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
            ['name' => 'Uherský Brod - Králov', 'type' => WarehouseType::MAIN],
            ['name' => 'Vozidlo Jaroslav', 'type' => WarehouseType::TEMPORARY],
            ['name' => 'Vozidlo Honza', 'type' => WarehouseType::TEMPORARY],
            ['name' => 'Vozidlo Tomáš', 'type' => WarehouseType::TEMPORARY],
            ['name' => 'Prodej', 'type' => WarehouseType::INTERNAL],
            ['name' => 'Kompost/Odpad ', 'type' => WarehouseType::TRASH],
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouse = $this->warehouseService->createWarehouse($warehouseData);
        }
    }
}

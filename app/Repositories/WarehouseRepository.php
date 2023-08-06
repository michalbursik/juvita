<?php


namespace App\Repositories;

use App\Enums\WarehouseTypeEnum;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseRepository
{
    public function store(array $attributes): Warehouse
    {
        $warehouse = new Warehouse($attributes);
        $warehouse->writeable()->save();

        return $warehouse;
    }

    public function update(Warehouse $warehouse, array $attributes): Warehouse
    {
        $warehouse->writeable()->update($attributes);

        return $warehouse->fresh();
    }

    public function destroy(Warehouse $warehouse): void
    {
        foreach ($warehouse->products as $warehouseProduct) {
            $warehouseProduct->remove();
        }

        $warehouse->writeable()->delete();
    }


    public function getTrashWarehouse(): Warehouse
    {
        $trashWarehouse = Cache::get('trash_warehouse');

        if (empty($trashWarehouse)) {
            $trashWarehouse = Warehouse::query()
                ->where('type', WarehouseTypeEnum::TRASH)
                ->first();

            Cache::set('trash_warehouse', $trashWarehouse);
        }

        return $trashWarehouse;
    }
}

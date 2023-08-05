<?php


namespace App\Repositories;

use App\Enums\WarehouseTypeEnum;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseRepository
{
    public function destroy($warehouseUuid): void
    {
        $warehouse = Warehouse::uuid($warehouseUuid);

        foreach ($warehouse->products as $warehouseProduct) {
            $warehouseProduct->remove();
        }

        $warehouse->writeable()->delete();
    }

    // TODO
//    public function update(Warehouse $warehouse, array $data): Warehouse
//    {
//        $warehouse->update($data);
//
//        return $warehouse;
//    }

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

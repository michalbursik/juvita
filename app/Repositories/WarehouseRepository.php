<?php


namespace App\Repositories;

use App\Enums\WarehouseTypeEnum;
use App\Exceptions\InsufficientAmountException;
use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WarehouseRepository
{
    public function store(array $data, ?Collection $products = null, array $pivotAttributes = []): Warehouse
    {
        if (empty($products)) {
            $products = Product::all();
        }

        if (empty($pivotAttributes)) {
            foreach ($products as $key => $product) {
                $pivotAttributes[$key] = [
                    'amount' => 0,
                    'price' => 0.00,
                ];
            }
        }

        if (empty($data['type'])) {
            $data['type'] = Warehouse::TYPE_TEMPORARY;
        }

        $warehouse = new Warehouse($data);

        $warehouse->save();

        $warehouse->products()->saveMany($products, $pivotAttributes);

        return $warehouse;
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->products()->detach();

        $warehouse->delete();
    }

    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);

        return $warehouse;
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

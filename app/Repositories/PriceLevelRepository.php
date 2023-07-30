<?php


namespace App\Repositories;


use App\Models\WarehouseProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceLevelRepository
{
    public function updateOrCreate(array $data): WarehouseProduct
    {
        $priceLevel = WarehouseProduct::query()
        ->where('warehouse_id', $data['warehouse_id'])
        ->where('product_id', $data['product_id'])
        ->where('price', $data['price'])
        ->first();

        if (empty($priceLevel)) {
            $priceLevel = new WarehouseProduct($data);
            $priceLevel->save();
        } else {
            $this->update($priceLevel, $data['amount']);
        }

        return $priceLevel;
    }

    /**
     * @param int $id
     * @return WarehouseProduct
     */
    public function get(int $id): WarehouseProduct
    {
        return WarehouseProduct::query()->find($id);
    }

    public function update(WarehouseProduct $priceLevel, float $amount): WarehouseProduct
    {
        $priceLevel->amount = round((float) $priceLevel->amount + (float) $amount, 1);
        $priceLevel->save();

        return $priceLevel;
    }

    public function delete(WarehouseProduct $priceLevel): ?bool
    {
        return $priceLevel->delete();
    }
}

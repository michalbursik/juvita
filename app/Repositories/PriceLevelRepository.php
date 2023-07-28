<?php


namespace App\Repositories;


use App\Models\ProductWarehouse;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceLevelRepository
{
    public function updateOrCreate(array $data): ProductWarehouse
    {
        $priceLevel = ProductWarehouse::query()
        ->where('warehouse_id', $data['warehouse_id'])
        ->where('product_id', $data['product_id'])
        ->where('price', $data['price'])
        ->first();

        if (empty($priceLevel)) {
            $priceLevel = new ProductWarehouse($data);
            $priceLevel->save();
        } else {
            $this->update($priceLevel, $data['amount']);
        }

        return $priceLevel;
    }

    /**
     * @param int $id
     * @return ProductWarehouse
     */
    public function get(int $id): ProductWarehouse
    {
        return ProductWarehouse::query()->find($id);
    }

    public function update(ProductWarehouse $priceLevel, float $amount): ProductWarehouse
    {
        $priceLevel->amount = round((float) $priceLevel->amount + (float) $amount, 1);
        $priceLevel->save();

        return $priceLevel;
    }

    public function delete(ProductWarehouse $priceLevel): ?bool
    {
        return $priceLevel->delete();
    }
}

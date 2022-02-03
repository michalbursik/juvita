<?php


namespace App\Repositories;


use App\Models\PriceLevel;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceLevelRepository
{
    public function updateOrCreate(array $data): PriceLevel
    {
        $priceLevel = PriceLevel::query()
        ->where('warehouse_id', $data['warehouse_id'])
        ->where('product_id', $data['product_id'])
        ->where('price', $data['price'])
        ->first();

        if (empty($priceLevel)) {
            $priceLevel = new PriceLevel($data);
            $priceLevel->save();
        } else {
            $this->update($priceLevel, $data['amount']);
        }

        return $priceLevel;
    }

    /**
     * @param int $id
     * @return PriceLevel
     */
    public function get(int $id): PriceLevel
    {
        return PriceLevel::query()->find($id);
    }

    public function update(PriceLevel $priceLevel, float $amount): PriceLevel
    {
        $priceLevel->amount += $amount;
        $priceLevel->save();

        return $priceLevel;
    }

    public function delete(PriceLevel $priceLevel): ?bool
    {
        return $priceLevel->delete();
    }
}

<?php


namespace App\Repositories;


use App\Models\PriceLevel;
use App\Models\Product;

class PriceLevelRepository
{
    public function getOrStore(array $data)
    {
        $priceLevel = PriceLevel::query()
            ->where('product_id', $data['product_id'])
            ->where('price', $data['price'])
            ->first();

        if (empty($priceLevel)) {
            $priceLevel = new PriceLevel($data);
            $priceLevel->save();
        }

        return $priceLevel;
    }
}

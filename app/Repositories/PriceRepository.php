<?php


namespace App\Repositories;

use App\Exceptions\InsufficientAmountException;
use App\Models\WarehouseProduct;
use App\Models\Price;
use Illuminate\Database\Eloquent\Model;

class PriceRepository
{
    public function receiveProduct(WarehouseProduct $warehouseProduct, float $price, float $amount): Price {
        $priceModel = $this->getOrCreatePrice($warehouseProduct, $price);

        $priceModel->amount += $amount;
        $priceModel->writeable()->save();

        return $priceModel;
    }

    public function getOrCreatePrice(WarehouseProduct $warehouseProduct, float $priceValue): Price {
        $priceModel = $this->get($warehouseProduct, $priceValue);

        if (empty($priceModel)) {
            $priceModel = Price::createWithAttributes([
                'warehouse_product_uuid' => $warehouseProduct->uuid,
                'price' => $priceValue,
                'amount' => 0,
            ]);
        }

        return $priceModel;
    }

    public function get(WarehouseProduct $warehouseProduct, float $priceValue): Price|Model|null
    {
        return $warehouseProduct->prices()->where('price', $priceValue)->first();
    }

    public function issueProduct(Price $price, float $amount): Price {
        if ($price->amount >= $amount) {
            $price->amount -= $amount;
            $price->writeable()->save();
        } else {
            throw new InsufficientAmountException('Amount of product on the warehouse is insufficient.');
        }

        return $price;
    }
}

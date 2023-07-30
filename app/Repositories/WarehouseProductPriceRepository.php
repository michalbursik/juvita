<?php


namespace App\Repositories;


use App\Exceptions\InsufficientAmountException;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductPrice;
use Illuminate\Database\Eloquent\Model;

class WarehouseProductPriceRepository
{
    public function receiveProduct(
        WarehouseProduct $warehouseProduct,
        float $price,
        float $amount
    ): WarehouseProductPrice {
        $warehouseProductPrice = $this->getOrCreateWarehouseProductPrice($warehouseProduct, $price);

        $warehouseProductPrice->amount += $amount;
        $warehouseProductPrice->writeable()->save();

        return $warehouseProductPrice;
    }

    public function getOrCreateWarehouseProductPrice(
        WarehouseProduct $warehouseProduct,
        float $price
    ): WarehouseProductPrice {
        $warehouseProductPrice = $this->get($warehouseProduct, $price);

        if (empty($warehouseProductPrice)) {
            $warehouseProductPrice = WarehouseProductPrice::createWithAttributes([
                'warehouse_product_uuid' => $warehouseProduct->uuid,
                'price' => $price,
                'amount' => 0,
            ]);
        }

        return $warehouseProductPrice;
    }

    public function get(WarehouseProduct $warehouseProduct, float $price): WarehouseProductPrice|Model|null
    {
        return $warehouseProduct->prices()->where('price', $price)->first();
    }

    public function issueProduct(
        WarehouseProduct $warehouseProduct,
        float $price,
        float $amount
    ): WarehouseProductPrice {
        $warehouseProductPrice = $this->getOrCreateWarehouseProductPrice($warehouseProduct, $price);

        if ($warehouseProductPrice->amount >= $amount) {
            $warehouseProductPrice->amount -= $amount;
            $warehouseProductPrice->writeable()->save();
        } else {
            throw new InsufficientAmountException('Amount of product on the warehouse is insufficient.');
        }

        return $warehouseProductPrice;
    }
}

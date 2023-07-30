<?php


namespace App\Repositories;


use App\Exceptions\InsufficientAmountException;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductPrice;
use Illuminate\Support\Collection;

class WarehouseProductRepository
{
    public function __construct(
        private readonly WarehouseProductPriceRepository $warehouseProductPriceRepository
    )
    {}

    public function get(string $warehouseUuid, string $productUuid): ?WarehouseProduct
    {
        return WarehouseProduct::exact($warehouseUuid, $productUuid)->first();
    }

    public function getOrCreate(string $warehouseUuid, string $productUuid): WarehouseProduct
    {
        $warehouseProduct = $this->get($warehouseUuid, $productUuid);

        if (empty($warehouseProduct)) {
            $warehouseProduct = WarehouseProduct::createWithAttributes([
               'warehouse_uuid' => $warehouseUuid,
               'product_uuid' => $productUuid,
               'total_amount' => 0
            ]);
        }

        return $warehouseProduct;
    }

    public function increaseTotalAmount(WarehouseProduct $warehouseProduct, float $amount): WarehouseProduct
    {
        $warehouseProduct->total_amount += $amount;
        $warehouseProduct->writeable()->save();

        return $warehouseProduct;
    }

    public function decreaseTotalAmount(WarehouseProduct $warehouseProduct, float $amount): WarehouseProduct
    {
        $warehouseProduct->total_amount -= $amount;
        $warehouseProduct->writeable()->save();

        return $warehouseProduct;
    }

    public function getWarehouseProductPrice(string $warehouseUuid, string $productUuid, float $price): WarehouseProductPrice
    {
        $warehouseProduct = $this->get($warehouseUuid, $productUuid);

        return $this->warehouseProductPriceRepository->getOrCreateWarehouseProductPrice($warehouseProduct, $price);
    }
}

<?php


namespace App\Repositories;


use App\Models\Price;
use App\Models\WarehouseProduct;

class WarehouseProductRepository
{
    public function __construct(
        private readonly PriceRepository $priceRepository
    ) {
    }

    public function getOrCreate(string $warehouseUuid, string $productUuid, int $order): WarehouseProduct
    {
        $warehouseProduct = $this->get($warehouseUuid, $productUuid);

        if (empty($warehouseProduct)) {
            $this->create($warehouseUuid, $productUuid, $order);
        }

        return $warehouseProduct;
    }

    public function get(string $warehouseUuid, string $productUuid): ?WarehouseProduct
    {
        return WarehouseProduct::exact($warehouseUuid, $productUuid)->first();
    }

    public function create(string $warehouseUuid, string $productUuid, int $order): WarehouseProduct
    {
        return WarehouseProduct::createWithAttributes([
            'warehouse_uuid' => $warehouseUuid,
            'product_uuid' => $productUuid,
            'total_amount' => 0,
            'order' => $order
        ]);
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

    public function getWarehouseProductPrice(string $warehouseUuid, string $productUuid, float $price): Price
    {
        $warehouseProduct = $this->get($warehouseUuid, $productUuid);

        return $this->priceRepository->getOrCreatePrice($warehouseProduct, $price);
    }
}

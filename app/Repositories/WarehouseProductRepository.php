<?php


namespace App\Repositories;


use App\Models\Price;
use App\Models\WarehouseProduct;

class WarehouseProductRepository
{
    public function __construct(
        private readonly PriceRepository $priceRepository
    ) {}

    public function get(string $warehouseUuid, string $productUuid): ?WarehouseProduct
    {
        return WarehouseProduct::exact($warehouseUuid, $productUuid)->first();
    }

    public function store(array $attributes): WarehouseProduct
    {
        $warehouseProduct = new WarehouseProduct($attributes);
        $warehouseProduct->writeable()->save();

        return $warehouseProduct;
    }

    public function update(WarehouseProduct $warehouseProduct, array $attributes): WarehouseProduct
    {
        $warehouseProduct->writeable()->update($attributes);

        return $warehouseProduct->fresh();
    }

    public function destroy(WarehouseProduct $warehouseProduct): ?bool
    {
        return $warehouseProduct->writeable()->delete();
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

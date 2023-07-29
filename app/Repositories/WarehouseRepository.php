<?php


namespace App\Repositories;


use App\Exceptions\InsufficientAmountException;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Collection;

class WarehouseRepository
{
    public function receiveProduct(
        string $warehouseUuid,
        string $productUuid,
        float $price,
        float $amount
    ): ProductWarehouse {
        $warehouseProduct = $this->getOrCreateProduct($warehouseUuid, $productUuid, $price);

        $warehouseProduct->amount += $amount;
        $warehouseProduct->writeable()->save();

        return $warehouseProduct;
    }

    public function productExists(string $warehouseUuid, string $productUuid, float $price): bool
    {
        return ProductWarehouse::query()->exact($warehouseUuid, $productUuid, $price)->exists();
    }

    public function getProduct(string $warehouseUuid, string $productUuid, float $price): ProductWarehouse
    {
        return ProductWarehouse::query()->exact($warehouseUuid, $productUuid, $price)->first();
    }

    public function getOrCreateProduct(string $warehouseUuid, string $productUuid, float $price): ProductWarehouse
    {
        if (!$this->productExists($warehouseUuid, $productUuid, $price)) {
            ProductWarehouse::createWithAttributes([
                'warehouse_uuid' => $warehouseUuid,
                'product_uuid' => $productUuid,
                'price' => $price,
                'amount' => 0,
            ]);
        }

        return $this->getProduct($warehouseUuid, $productUuid, $price);
    }

    public function issueProduct(
        string $warehouseUuid,
        string $productUuid,
        float $price,
        float $amount
    ): ProductWarehouse {
        $warehouseProduct = $this->getOrCreateProduct($warehouseUuid, $productUuid, $price);

        if ($warehouseProduct->amount >= $amount) {
            $warehouseProduct->amount -= $amount;
            $warehouseProduct->writeable()->save();
        } else {
            throw new InsufficientAmountException('Amount of product on the warehouse is insufficient.');
        }

        return $warehouseProduct;
    }

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
}

<?php

namespace App\Domain\Warehouse\Aggregates;

use App\Domain\Warehouse\Events\InventoryChecked;
use App\Domain\Warehouse\Events\StockIssued;
use App\Domain\Warehouse\Events\StockReceived;
use App\Domain\Warehouse\Events\StockTransferred;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class WarehouseAggregate extends AggregateRoot
{
    public function receiveStock(string $productId, string $warehouseId, float $amount, float $price, string $userId): self
    {
        $this->recordThat(new StockReceived(
            productId: $productId,
            warehouseId: $warehouseId,
            amount: $amount,
            price: $price,
            userId: $userId
        ));

        return $this;
    }

    public function issueStock(string $productId, string $warehouseId, string $priceLevelId, float $amount, string $userId): self
    {
        $this->recordThat(new StockIssued(
            productId: $productId,
            warehouseId: $warehouseId,
            priceLevelId: $priceLevelId,
            amount: $amount,
            userId: $userId
        ));

        return $this;
    }

    public function transferStock(string $productId, string $fromWarehouseId, string $toWarehouseId, string $priceLevelId, float $amount, string $userId): self
    {
        $this->recordThat(new StockTransferred(
            productId: $productId,
            fromWarehouseId: $fromWarehouseId,
            toWarehouseId: $toWarehouseId,
            priceLevelId: $priceLevelId,
            amount: $amount,
            userId: $userId
        ));

        return $this;
    }

    public function checkInventory(string $warehouseId, string $userId, float $discount, array $products): self
    {
        $this->recordThat(new InventoryChecked(
            warehouseId: $warehouseId,
            userId: $userId,
            discount: $discount,
            products: $products
        ));

        return $this;
    }
}

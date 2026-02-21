<?php

namespace App\Domain\Warehouse\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class StockTransferred extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $fromWarehouseId,
        public string $toWarehouseId,
        public string $priceLevelId,
        public float $amount,
        public string $userId,
        public ?string $movementId = null,
    ) {}
}

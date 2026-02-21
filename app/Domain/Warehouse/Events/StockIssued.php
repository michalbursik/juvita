<?php

namespace App\Domain\Warehouse\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class StockIssued extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $warehouseId,
        public string $priceLevelId,
        public float $amount,
        public string $userId,
        public ?string $movementId = null,
    ) {}
}

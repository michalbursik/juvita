<?php

namespace App\Domain\Warehouse\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class StockReceived extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $warehouseId,
        public float $amount,
        public float $price,
        public string $userId,
        public ?string $movementId = null,
    ) {}
}

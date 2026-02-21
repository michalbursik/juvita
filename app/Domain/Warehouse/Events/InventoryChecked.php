<?php

namespace App\Domain\Warehouse\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InventoryChecked extends ShouldBeStored
{
    public function __construct(
        public string $warehouseId,
        public string $userId,
        public float $discount,
        public array $products, // Each product with id, amount_before, amount_after, price_level_id, price
        public ?string $checkId = null,
    ) {}
}

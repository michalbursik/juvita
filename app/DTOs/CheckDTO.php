<?php

namespace App\DTOs;

class CheckDTO
{
    public function __construct(
        public string $warehouseId,
        public string $userId,
        public array $products, // items with product_id, price_level_id, amount
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            warehouseId: $validated['warehouse_id'],
            userId: auth()->id(),
            products: $validated['products'],
        );
    }
}

<?php

namespace App\DTOs;

class MovementDTO
{
    public function __construct(
        public string $productId,
        public float $amount,
        public string $userId,
        public ?string $receiptWarehouseId = null,
        public ?string $issueWarehouseId = null,
        public ?float $price = null,
        public ?string $priceLevelId = null,
        public ?string $type = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            productId: $validated['product_id'],
            amount: (float) $validated['amount'],
            userId: $validated['user_id'] ?? auth()->id(),
            receiptWarehouseId: $validated['receipt_warehouse_id'] ?? null,
            issueWarehouseId: $validated['issue_warehouse_id'] ?? null,
            price: isset($validated['price']) ? (float) $validated['price'] : null,
            priceLevelId: $validated['price_level_id'] ?? null,
            type: $validated['type'] ?? null,
        );
    }
}

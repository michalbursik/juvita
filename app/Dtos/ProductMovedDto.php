<?php

namespace App\Dtos;

class ProductMovedDto
{
    public function __construct(
        public readonly string $sourceWarehouseUuid,
        public readonly string $targetWarehouseUuid,
        public readonly string $productUuid,
        public readonly string $userUuid,
        public readonly string $priceUuid,
        public readonly float $amount,
    ) {}

}

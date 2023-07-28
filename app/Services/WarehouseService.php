<?php

namespace App\Services;

use App\Managers\WarehouseManager;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;

class WarehouseService
{
    public function __construct(
        public readonly Warehouse $warehouse,
    ) {}

    public function processMovement(Movement $movement): ProductWarehouse
    {
        $method = $movement->type;

        $movement->amount;
        $movement->price;

        dd(
            $movement->issueWarehouse
        );

        // product + warehouse => priceLevel

        // TODO:
        // PriceLevel is pivot between product and warehouse
        // WarehouseProduct is also pivot between product and warehouse
        // -----

        // Warehouse -> Products -> PriceLevels
        // PriceLevel -> Warehouse -> Product
        // WarehouseProduct -> Warehouse -> Product


        // return $product;
    }
}

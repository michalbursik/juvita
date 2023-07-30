<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Events\WarehouseProductPriceCreated;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductPrice;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductPriceProjector extends Projector
{
    public function onWarehouseProductPriceCreated(WarehouseProductPriceCreated $event): void
    {
        (new WarehouseProductPrice($event->warehouseProductPriceAttributes))->writeable()->save();
    }
}

<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Events\PriceCreated;
use App\Models\WarehouseProduct;
use App\Models\Price;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductPriceProjector extends Projector
{
    public function onWarehouseProductPriceCreated(PriceCreated $event): void
    {
        (new Price($event->warehouseProductPriceAttributes))->writeable()->save();
    }
}

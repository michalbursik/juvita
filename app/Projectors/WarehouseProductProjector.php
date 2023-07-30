<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Models\WarehouseProduct;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductProjector extends Projector
{
    public function onWarehouseProductCreated(WarehouseProductCreated $event): void
    {
        (new WarehouseProduct($event->warehouseProductAttributes))->writeable()->save();
    }
}

<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Events\WarehouseProductRemoved;
use App\Events\WarehouseRemoved;
use App\Models\WarehouseProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductProjector extends Projector implements ShouldQueue
{
    public function onWarehouseProductCreated(WarehouseProductCreated $event): void
    {
        (new WarehouseProduct($event->warehouseProductAttributes))->writeable()->save();
    }

    public function onWarehouseProductRemoved(WarehouseProductRemoved $event): void
    {
        $warehouseProduct = WarehouseProduct::uuid($event->warehouseProductUuid);

        $warehouseProduct->writeable()->delete();
    }
}

<?php

namespace App\Projectors;

use App\Events\WarehouseCreated;
use App\Models\Warehouse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProjector extends Projector implements ShouldQueue
{
    public function onWarehouseCreated(WarehouseCreated $event): void
    {
        (new Warehouse($event->warehouseAttributes))->writeable()->save();

    }
}

<?php

namespace App\Projectors;

use App\Events\ProductWarehouseCreated;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductWarehouseProjector extends Projector
{
    public function onProductWarehouseCreated(ProductWarehouseCreated $event): void
    {
        (new ProductWarehouse($event->productWarehouseAttributes))->writeable()->save();
    }
}

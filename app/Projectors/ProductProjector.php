<?php

namespace App\Projectors;

use App\Events\ProductCreated;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductProjector extends Projector implements ShouldQueue
{
    public function onProductCreated(ProductCreated $event): void
    {
        (new Product($event->productAttributes))->writeable()->save();
    }
}

<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Events\PriceCreated;
use App\Models\WarehouseProduct;
use App\Models\Price;
use App\Repositories\WarehouseProductPriceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductPriceProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseProductPriceRepository $warehouseProductPriceRepository
    ) {}

    public function onWarehouseProductPriceCreated(PriceCreated $event): void
    {
        $this->warehouseProductPriceRepository->store($event->warehouseProductPriceAttributes);
    }
}

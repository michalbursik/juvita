<?php

namespace App\Projectors;

use App\Events\WarehouseProductCreated;
use App\Events\WarehouseProductModified;
use App\Events\WarehouseProductRemoved;
use App\Events\WarehouseRemoved;
use App\Models\WarehouseProduct;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository
    ) {}

    public function onWarehouseProductCreated(WarehouseProductCreated $event): void
    {
        $this->warehouseProductRepository->store($event->warehouseProductAttributes);
    }

    public function onWarehouseProductModified(WarehouseProductModified $event): void
    {
        $warehouseProduct = WarehouseProduct::uuid($event->productUuid);

        $this->warehouseProductRepository->update($warehouseProduct, $event->productAttributes);
    }

    public function onWarehouseProductRemoved(WarehouseProductRemoved $event): void
    {
        $warehouseProduct = WarehouseProduct::uuid($event->warehouseProductUuid);

        $this->warehouseProductRepository->destroy($warehouseProduct);
    }
}

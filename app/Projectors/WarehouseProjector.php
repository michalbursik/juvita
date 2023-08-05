<?php

namespace App\Projectors;

use App\Events\WarehouseCreated;
use App\Events\WarehouseRemoved;
use App\Events\WarehouseModified;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\WarehouseProductRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository,
        private readonly WarehouseRepository $warehouseRepository,
    ) {}

    public function onWarehouseCreated(WarehouseCreated $event): void
    {
        $products = Product::all();

        $warehouse = new Warehouse($event->warehouseAttributes);
        $warehouse->writeable()->save();

        /** @var Product $product */
        foreach ($products as $product) {
            $this->warehouseProductRepository->create($warehouse->uuid, $product->uuid);
        }
    }

    public function onWarehouseModified(WarehouseModified $event): void
    {
        $warehouse = Warehouse::uuid($event->warehouseUuid);

        $warehouse->writeable()->update($event->warehouseAttributes);
    }

    public function onWarehouseRemoved(WarehouseRemoved $event): void
    {
        $this->warehouseRepository->destroy($event->warehouseUuid);
    }
}

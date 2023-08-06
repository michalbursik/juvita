<?php

namespace App\Projectors;

use App\Events\WarehouseCreated;
use App\Events\WarehouseRemoved;
use App\Events\WarehouseModified;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Repositories\WarehouseProductRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseRepository $warehouseRepository,
    ) {}

    public function onWarehouseCreated(WarehouseCreated $event): void
    {
        $warehouse = $this->warehouseRepository->store($event->warehouseAttributes);

        $products = Product::all();

        /** @var Product $product */
        foreach ($products as $product) {
            WarehouseProduct::createWithAttributes([
                'warehouse_uuid' => $warehouse->uuid,
                'product_uuid' => $product->uuid,
                'total_amount' => 0,
                'order' => $product->order
            ]);
        }
    }

    public function onWarehouseModified(WarehouseModified $event): void
    {
        $warehouse = Warehouse::uuid($event->warehouseUuid);

        $this->warehouseRepository->update($warehouse, $event->warehouseAttributes);
    }

    public function onWarehouseRemoved(WarehouseRemoved $event): void
    {
        $warehouse = Warehouse::uuid($event->warehouseUuid);

        $this->warehouseRepository->destroy($warehouse);
    }
}

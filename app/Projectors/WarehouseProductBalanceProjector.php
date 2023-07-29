<?php

namespace App\Projectors;

use App\Events\ProductMoved;
use App\Events\ProductReceived;
use App\Exceptions\InsufficientAmountException;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProductBalanceProjector extends Projector implements ShouldQueue
{

    public function __construct(private readonly WarehouseRepository $warehouseRepository){}

    public function onProductReceived(ProductReceived $event): void
    {
        $this->warehouseRepository->receiveProduct(
            $event->warehouseUuid,
            $event->productUuid,
            $event->price,
            $event->amount
        );
    }

    /**
     * @throws InsufficientAmountException
     */
    public function onProductWarehouseMoved(ProductMoved $event): void
    {
        if ($event->targetWarehouseUuid) {
            $this->warehouseRepository->issueProduct(
                $event->sourceWarehouseUuid,
                $event->productUuid,
                $event->price,
                $event->amount
            );
        }

        $this->warehouseRepository->receiveProduct(
            $event->targetWarehouseUuid,
            $event->productUuid,
            $event->price,
            $event->amount
        );
    }
}

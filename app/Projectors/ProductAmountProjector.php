<?php

namespace App\Projectors;

use App\Events\ProductMoved;
use App\Events\ProductReceived;
use App\Exceptions\InsufficientAmountException;
use App\Models\WarehouseProduct;
use App\Models\Warehouse;
use App\Repositories\WarehouseProductPriceRepository;
use App\Repositories\WarehouseProductRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductAmountProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository,
        private readonly WarehouseProductPriceRepository $warehouseProductPriceRepository,
    ){}

    public function onProductReceived(ProductReceived $event): void
    {
        $warehouseProduct = $this->warehouseProductRepository->getOrCreate(
            $event->warehouseUuid,
            $event->productUuid,
        );

        $this->warehouseProductRepository->increaseTotalAmount(
            $warehouseProduct, $event->amount
        );

        $this->warehouseProductPriceRepository->receiveProduct(
            $warehouseProduct,
            $event->price,
            $event->amount
        );
    }

    /**
     * @throws InsufficientAmountException
     */
    public function onProductWarehouseMoved(ProductMoved $event): void
    {
        // Issue from source warehouse
        if ($event->sourceWarehouseUuid) {
            $sourceWarehouseProduct = $this->warehouseProductRepository->getOrCreate(
                $event->sourceWarehouseUuid,
                $event->productUuid,
            );

            $this->warehouseProductRepository->decreaseTotalAmount(
                $sourceWarehouseProduct, $event->amount
            );

            $this->warehouseProductPriceRepository->issueProduct(
                $sourceWarehouseProduct,
                $event->price,
                $event->amount
            );
        }

        // Receipt to target warehouse
        $targetWarehouseProduct = $this->warehouseProductRepository->getOrCreate(
            $event->targetWarehouseUuid,
            $event->productUuid,
        );

        $this->warehouseProductRepository->increaseTotalAmount(
            $targetWarehouseProduct, $event->amount
        );

        $this->warehouseProductPriceRepository->receiveProduct(
            $targetWarehouseProduct,
            $event->price,
            $event->amount
        );
    }
}

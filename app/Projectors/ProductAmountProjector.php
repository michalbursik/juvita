<?php

namespace App\Projectors;

use App\Events\ProductMoved;
use App\Events\ProductReceived;
use App\Events\ProductTrashed;
use App\Exceptions\InsufficientAmountException;
use App\Models\Price;
use App\Models\WarehouseProduct;
use App\Repositories\PriceRepository;
use App\Repositories\WarehouseProductRepository;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductAmountProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository,
        private readonly PriceRepository $priceRepository,
    ) {
    }

    public function onProductReceived(ProductReceived $event): void
    {
        $warehouseProduct = $this->warehouseProductRepository->getOrCreate(
            $event->warehouseUuid,
            $event->productUuid,
        );

        $this->warehouseProductRepository->increaseTotalAmount(
            $warehouseProduct,
            $event->amount
        );

        $this->priceRepository->receiveProduct(
            $warehouseProduct,
            $event->price,
            $event->amount
        );
    }

    public function onProductTrashed(ProductTrashed $event): void
    {
        DB::beginTransaction();

        try {
            // Issue from source warehouse
            $sourceWarehouseProduct = WarehouseProduct::uuid($event->sourceWarehouseProductUuid);
            $this->warehouseProductRepository->decreaseTotalAmount($sourceWarehouseProduct, $event->amount);

            $price = Price::uuid($event->priceUuid);
            $this->priceRepository->issueProduct($price, $event->amount);

            // Receive on target trash warehouse
            $trashWarehouseProduct = WarehouseProduct::uuid($event->targetWarehouseProductUuid);
            $this->warehouseProductRepository->increaseTotalAmount($trashWarehouseProduct, $event->amount);

            $this->priceRepository->receiveProduct(
                $trashWarehouseProduct,
                $price->price,
                $event->amount
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param ProductMoved $event
     * @throws InsufficientAmountException|Exception
     */
    public function onProductMoved(ProductMoved $event): void
    {
        DB::beginTransaction();
        try {
            // Issue from source warehouse
            $sourceWarehouseProduct = $this->warehouseProductRepository->getOrCreate(
                $event->sourceWarehouseUuid,
                $event->productUuid,
            );

            $this->warehouseProductRepository->decreaseTotalAmount(
                $sourceWarehouseProduct,
                $event->amount
            );

            $price = Price::uuid($event->priceUuid);

            $this->priceRepository->issueProduct(
                $price,
                $event->amount
            );

            // Receipt to target warehouse
            $targetWarehouseProduct = $this->warehouseProductRepository->getOrCreate(
                $event->targetWarehouseUuid,
                $event->productUuid,
            );

            $this->warehouseProductRepository->increaseTotalAmount(
                $targetWarehouseProduct,
                $event->amount
            );

            $this->priceRepository->receiveProduct(
                $targetWarehouseProduct,
                $price->price,
                $event->amount
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

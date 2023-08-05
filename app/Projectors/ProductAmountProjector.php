<?php

namespace App\Projectors;

use App\Enums\MovementTypeEnum;
use App\Events\ProductMoved;
use App\Events\ProductReceived;
use App\Events\ProductTrashed;
use App\Exceptions\InsufficientAmountException;
use App\Models\Movement;
use App\Models\Price;
use App\Models\User;
use App\Models\WarehouseProduct;
use App\Repositories\MovementRepository;
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
        private readonly MovementRepository $movementRepository,
    ) {
    }

    public function onProductReceived(ProductReceived $event): void
    {
        $warehouseProduct = $this->warehouseProductRepository->get(
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

        $this->movementRepository->store([
            'source_warehouse_uuid' => null,
            'target_warehouse_uuid' => $warehouseProduct->warehouse_uuid,
            'product_uuid' => $event->productUuid,
            'type' => MovementTypeEnum::RECEIVE,
            'amount' => $event->amount,
            'price' => $event->price,
            'user_uuid' => $event->userUuid
        ]);
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

            $this->movementRepository->store([
                'source_warehouse_uuid' => $sourceWarehouseProduct->warehouse_uuid,
                'target_warehouse_uuid' => $trashWarehouseProduct->warehouse_uuid,
                'product_uuid' => $sourceWarehouseProduct->product_uuid,
                'type' => MovementTypeEnum::MOVE,
                'amount' => $event->amount,
                'price' => $price->price,
                'user_uuid' => $event->userUuid
            ]);

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
            $sourceWarehouseProduct = $this->warehouseProductRepository->get(
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
            $targetWarehouseProduct = $this->warehouseProductRepository->get(
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

            $this->movementRepository->store([
                'source_warehouse_uuid' => $event->sourceWarehouseUuid,
                'target_warehouse_uuid' => $event->targetWarehouseUuid,
                'product_uuid' => $event->productUuid,
                'type' => MovementTypeEnum::MOVE,
                'amount' => $event->amount,
                'price' => $price->price,
                'user_uuid' => $event->userUuid
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

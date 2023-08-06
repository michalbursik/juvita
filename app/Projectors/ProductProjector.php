<?php

namespace App\Projectors;

use App\Events\ProductCreated;
use App\Events\ProductModified;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Repositories\ProductRepository;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function onProductCreated(ProductCreated $event): void
    {
        $product = $this->productRepository->store($event->productAttributes);

        /** @var Warehouse $warehouse */
        foreach (Warehouse::all() as $warehouse) {
            WarehouseProduct::createWithAttributes([
                'warehouse_uuid' => $warehouse->uuid,
                'product_uuid' => $product->uuid,
                'total_amount' => 0,
                'order' => $product->order
            ]);
        }
    }

    public function onProductModified(ProductModified $event): void
    {
        $product = Product::uuid($event->productUuid);
        $this->productRepository->update($product, $event->productAttributes);

        $warehouseProducts = WarehouseProduct::query()->where('product_uuid', $product->uuid)->get();
        foreach ($warehouseProducts as $warehouseProduct) {
            $warehouseProduct->modify($event->productAttributes);
        }
    }
}

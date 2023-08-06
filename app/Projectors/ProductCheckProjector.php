<?php

namespace App\Projectors;

use App\Enums\MovementTypeEnum;
use App\Events\ProductCheckCreated;
use App\Models\Movement;
use App\Models\Price;
use App\Repositories\PriceRepository;
use App\Repositories\ProductCheckRepository;
use App\Repositories\WarehouseProductRepository;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ProductCheckProjector extends Projector
{
    public function __construct(
        private readonly WarehouseProductRepository $warehouseProductRepository,
        private readonly PriceRepository $priceRepository,
        private readonly ProductCheckRepository $productCheckRepository,
    ) {
    }

    public function onProductCheckCreated(ProductCheckCreated $event): void
    {
        $productCheck = $this->productCheckRepository->store($event->productCheckAttributes);

        $price = Price::uuid($event->productCheckAttributes['price_uuid']);
        $amount = $productCheck->amount_before - $productCheck->amount_after;

        $this->warehouseProductRepository->decreaseTotalAmount($productCheck->product, $amount);

        // TODO what if it fails here ???
        // -> Pending => Successful / Failed check ?
        // ->
        $this->priceRepository->issueProduct($price, $amount);

        Movement::createWithAttributes([
            'source_warehouse_uuid' => $productCheck->product->warehouse_uuid,
            'target_warehouse_uuid' => null,
            'product_uuid' => $productCheck->product->product_uuid,
            'type' => MovementTypeEnum::CHECK,
            'amount' => $amount,
            'price' => $productCheck->price,
            'user_uuid' => $event->productCheckAttributes['user_uuid']
        ]);
    }
}

<?php

use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Models\Warehouse;
use App\Models\WarehouseProductPrice;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var Warehouse $warehouse */
    $warehouse = Warehouse::factory()->create();

    $this->warehouse = $warehouse;

    /** @var WarehouseProductRepository $warehouseProductRepository */
    $warehouseProductRepository = app(WarehouseProductRepository::class);
    $this->warehouseProductRepository = $warehouseProductRepository;
});

it('creates or gets non existing warehouse product price', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    $price = 100;

    $warehouseProductPrice = $this->warehouseProductRepository->getWarehouseProductPrice(
        $this->warehouse->uuid, $product->uuid, $price
    );

    expect($warehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($warehouseProductPrice->uuid)->toBeString()->toBe($warehouseProductPrice->uuid);

    $nextProductWarehouse = $this->warehouseProductRepository->getWarehouseProductPrice(
        $this->warehouse->uuid, $product->uuid, $price
    );

    expect($nextProductWarehouse)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($nextProductWarehouse->id)->toBeInt()->toBe(1)
        ->and($nextProductWarehouse->uuid)->toBeString()->toBe($warehouseProductPrice->uuid);
})->skip();

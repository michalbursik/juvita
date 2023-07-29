<?php

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates or gets non existing product', function () {
    $repository = new WarehouseRepository();
    /** @var Product $product */
    $product = Product::factory()->create();
    /** @var Warehouse $warehouse */
    $warehouse = Warehouse::factory()->create();
    $price = 100;

    $productWarehouse = $repository->getOrCreateProduct($warehouse->uuid, $product->uuid, $price);

    expect($productWarehouse)->toBeInstanceOf(ProductWarehouse::class)
        ->and($productWarehouse->uuid)->toBeString()->toBe($productWarehouse->uuid);

    $nextProductWarehouse = $repository->getOrCreateProduct($warehouse->uuid, $product->uuid, $price);

    expect($nextProductWarehouse)->toBeInstanceOf(ProductWarehouse::class)
        ->and($nextProductWarehouse->id)->toBeInt()->toBe(1)
        ->and($nextProductWarehouse->uuid)->toBeString()->toBe($productWarehouse->uuid);
});

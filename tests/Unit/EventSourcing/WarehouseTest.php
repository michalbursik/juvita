<?php

use App\Exceptions\InsufficientAmountException;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var Warehouse $sourceWarehouse */
    $sourceWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse = $sourceWarehouse;
});

test('warehouse can be created', function () {
    expect($this->sourceWarehouse->uuid)->toBeString();
});

test('warehouse can receive a product', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);

    $warehouseProduct = ProductWarehouse::query()
        ->exact($this->sourceWarehouse, $product, 100)
        ->first();

    expect($warehouseProduct)->toBeInstanceOf(ProductWarehouse::class)
        ->and($warehouseProduct->amount)->toBeFloat()->toBe(10.0)
        ->and($warehouseProduct->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can receive more products', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);

    $warehouseProduct = ProductWarehouse::query()
        ->exact($this->sourceWarehouse, $product, 100)
        ->first();

    expect($warehouseProduct)->toBeInstanceOf(ProductWarehouse::class)
        ->and($warehouseProduct->amount)->toBeFloat()->toBe(30.0)
        ->and($warehouseProduct->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can move a received product within amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);

    $sourceWarehouseProduct = ProductWarehouse::query()
        ->exact($this->sourceWarehouse, $product, 100)
        ->first();

    $targetWarehouseProduct = ProductWarehouse::query()
        ->exact($targetWarehouse, $product, 100)
        ->first();

    expect($sourceWarehouseProduct)->toBeInstanceOf(ProductWarehouse::class)
        ->and($sourceWarehouseProduct->amount)->toBeFloat()->toBe(5.0)
        ->and($sourceWarehouseProduct->price)->toBeFloat()->toBe(100.0)
        ->and($targetWarehouseProduct)->toBeInstanceOf(ProductWarehouse::class)
        ->and($targetWarehouseProduct->amount)->toBeFloat()->toBe(5.0)
        ->and($targetWarehouseProduct->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can move more received products within amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);

    $warehouseProduct = ProductWarehouse::query()
        ->exact($this->sourceWarehouse, $product, 100)
        ->first();

    expect($warehouseProduct)->toBeInstanceOf(ProductWarehouse::class)
        ->and($warehouseProduct->amount)->toBeFloat()->toBe(0.0)
        ->and($warehouseProduct->price)->toBeFloat()->toBe(100.0);
});

test('warehouse cannot move received product over his amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 15);
})->throws(InsufficientAmountException::class);

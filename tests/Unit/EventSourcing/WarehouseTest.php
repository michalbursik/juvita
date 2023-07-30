<?php

use App\Exceptions\InsufficientAmountException;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductPrice;
use App\Repositories\WarehouseProductPriceRepository;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var Warehouse $sourceWarehouse */
    $sourceWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse = $sourceWarehouse;

    /** @var WarehouseProductRepository $warehouseProductRepository */
    $warehouseProductRepository = app(WarehouseProductRepository::class);
    $this->warehouseProductRepository = $warehouseProductRepository;

    /** @var WarehouseProductPriceRepository $warehouseProductPriceRepository */
    $warehouseProductPriceRepository = app(WarehouseProductPriceRepository::class);
    $this->warehouseProductPriceRepository = $warehouseProductPriceRepository;
});

test('warehouse can be created', function () {
    expect($this->sourceWarehouse->uuid)->toBeString();
});

test('warehouse can receive a product', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);

    $warehouseProduct = $this->warehouseProductRepository->get(
        $this->sourceWarehouse->uuid, $product->uuid
    );

    $warehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($warehouseProduct, 100);

    expect($warehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($warehouseProduct->total_amount)->toBeFloat()->toBe(10.0)
        ->and($warehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($warehouseProductPrice->amount)->toBeFloat()->toBe(10.0)
        ->and($warehouseProductPrice->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can receive more products', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);

    $warehouseProduct = $this->warehouseProductRepository->get(
        $this->sourceWarehouse->uuid, $product->uuid
    );

    $warehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($warehouseProduct, 100);

    expect($warehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($warehouseProduct->total_amount)->toBeFloat()->toBe(30.0)
        ->and($warehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($warehouseProductPrice->amount)->toBeFloat()->toBe(30.0)
        ->and($warehouseProductPrice->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can move a received product within amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);

    $sourceWarehouseProduct = $this->warehouseProductRepository->get(
        $this->sourceWarehouse->uuid, $product->uuid
    );

    $sourceWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($sourceWarehouseProduct, 100);

    expect($sourceWarehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($sourceWarehouseProduct->total_amount)->toBeFloat()->toBe(5.0)
        ->and($sourceWarehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($sourceWarehouseProductPrice->amount)->toBeFloat()->toBe(5.0)
        ->and($sourceWarehouseProductPrice->price)->toBeFloat()->toBe(100.0);

    $targetWarehouseProduct = $this->warehouseProductRepository->get(
        $targetWarehouse->uuid, $product->uuid
    );

    $targetWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($targetWarehouseProduct, 100);

    expect($targetWarehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($targetWarehouseProduct->total_amount)->toBeFloat()->toBe(5.0)
        ->and($targetWarehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($targetWarehouseProductPrice->amount)->toBeFloat()->toBe(5.0)
        ->and($targetWarehouseProductPrice->price)->toBeFloat()->toBe(100.0);
});

test('warehouse can move more received products within amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 5);

    $sourceWarehouseProduct = $this->warehouseProductRepository->get(
        $this->sourceWarehouse->uuid, $product->uuid
    );

    $sourceWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($sourceWarehouseProduct, 100);

    expect($sourceWarehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($sourceWarehouseProduct->total_amount)->toBeFloat()->toBe(0.0)
        ->and($sourceWarehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($sourceWarehouseProductPrice->amount)->toBeFloat()->toBe(0.0)
        ->and($sourceWarehouseProductPrice->price)->toBeFloat()->toBe(100.0);

    $targetWarehouseProduct = $this->warehouseProductRepository->get(
        $targetWarehouse->uuid, $product->uuid
    );

    $targetWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->getOrCreateWarehouseProductPrice($targetWarehouseProduct, 100);

    expect($targetWarehouseProduct)->toBeInstanceOf(WarehouseProduct::class)
        ->and($targetWarehouseProduct->total_amount)->toBeFloat()->toBe(10.0)
        ->and($targetWarehouseProductPrice)->toBeInstanceOf(WarehouseProductPrice::class)
        ->and($targetWarehouseProductPrice->amount)->toBeFloat()->toBe(10.0)
        ->and($targetWarehouseProductPrice->price)->toBeFloat()->toBe(100.0);
});

test('warehouse cannot move received product over his amount', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse->receiveProduct($product->uuid, 100, 10);
    $this->sourceWarehouse->moveProduct($targetWarehouse->uuid, $product->uuid, 100, 15);
})->throws(InsufficientAmountException::class);

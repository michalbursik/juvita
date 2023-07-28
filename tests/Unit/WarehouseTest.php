<?php

use App\Models\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Services\WarehouseService;

it('can receive product', function () {
    /** @var Warehouse $warehouse */
    $warehouse = Warehouse::factory()->create();

    /** @var Movement $movement */
    $movement = Movement::factory()->receiptTo($warehouse)->create();

    $service = new WarehouseService($warehouse);
    $product = $service->processMovement($movement);


    expect($product)->toBeInstanceOf(ProductWarehouse::class)
        ->and($product->amount)->toBeFloat()->toBe(100)
        ->and($product->price)->toBeFloat()->toBe(10);
});

it('can issue product', function () {

})->skip('TODO');

it('can transmission product', function () {

})->skip('TODO');

it('can unload product - out of stock', function () {

})->skip('TODO');

it('can unload product - reduce amount', function () {

})->skip('TODO');

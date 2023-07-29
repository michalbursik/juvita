<?php

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var Warehouse $sourceWarehouse */
    $sourceWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse = $sourceWarehouse;

    $user = User::factory()->create();
    $this->actingAs($user);
});

test('warehouse can receive a product through the api', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    $url = "/api/warehouses/receive";
    $data = [
        'warehouse_uuid' => $this->sourceWarehouse->uuid,
        'product_uuid' => $product->uuid,
        'amount' => 100,
        'price' => 50,
    ];

    $response = $this->post($url, $data);

    expect($response->status())->toBeInt()->toBe(202);
});

test('warehouse can move received product through the api', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();
    $price = 100;

    $this->sourceWarehouse->receiveProduct($product->uuid, $price, 20);

    $url = "/api/warehouses/move";
    $data = [
        'source_warehouse_uuid' => $this->sourceWarehouse->uuid,
        'target_warehouse_uuid' => $targetWarehouse->uuid,
        'product_uuid' => $product->uuid,
        'price' => 100,
        'amount' => 10,
    ];

    $response = $this->post($url, $data);

    $sourceWarehouseProduct = ProductWarehouse::query()->exact($this->sourceWarehouse, $product, 100)->first();
    $targetWarehouseProduct = ProductWarehouse::query()->exact($targetWarehouse, $product, 100)->first();

    expect($response->status())->toBeInt()->toBe(202)
        ->and($sourceWarehouseProduct->amount)->toBeFloat()->toBe(10.0)
        ->and($targetWarehouseProduct->amount)->toBeFloat()->toBe(10.0);
});

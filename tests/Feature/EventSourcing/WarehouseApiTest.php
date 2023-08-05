<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\PriceRepository;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var Warehouse $sourceWarehouse */
    $sourceWarehouse = Warehouse::factory()->create();

    $this->sourceWarehouse = $sourceWarehouse;

    $user = User::factory()->create();
    $this->actingAs($user);

    /** @var WarehouseProductRepository $warehouseProductRepository */
    $warehouseProductRepository = app(WarehouseProductRepository::class);
    $this->warehouseProductRepository = $warehouseProductRepository;

    /** @var PriceRepository $warehouseProductPriceRepository */
    $warehouseProductPriceRepository = app(PriceRepository::class);
    $this->warehouseProductPriceRepository = $warehouseProductPriceRepository;
});

test('warehouse can receive a product through the api', function () {
    /** @var Product $product */
    $product = Product::factory()->create();
    $url = "/api/warehouses/products/receive";
    $data = [
        'warehouse_uuid' => $this->sourceWarehouse->uuid,
        'product_uuid' => $product->uuid,
        'amount' => 100,
        'price' => 50,
    ];

    $response = $this->post($url, $data);

    $warehouseProduct = $this->warehouseProductRepository
        ->get($this->sourceWarehouse->uuid, $product->uuid);

    $warehouseProductPrice = $this->warehouseProductPriceRepository
        ->get($warehouseProduct, 50);

    expect($response->status())->toBeInt()->toBe(202)
        ->and($warehouseProduct->total_amount)->toBeFloat()->toBe(100.0)
        ->and($warehouseProductPrice->price)->toBeFloat()->toBe(50.0)
        ->and($warehouseProductPrice->amount)->toBeFloat()->toBe(100.0);
});

test('warehouse can move received product through the api', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Warehouse $targetWarehouse */
    $targetWarehouse = Warehouse::factory()->create();
    $price = 100;

    $this->sourceWarehouse->receiveProduct($product->uuid, $price, 20);

    $url = "/api/warehouses/products/move";
    $data = [
        'source_warehouse_uuid' => $this->sourceWarehouse->uuid,
        'target_warehouse_uuid' => $targetWarehouse->uuid,
        'product_uuid' => $product->uuid,
        'price' => 100,
        'amount' => 10,
    ];

    $response = $this->post($url, $data);

    $sourceWarehouseProduct = $this->warehouseProductRepository
        ->get($this->sourceWarehouse->uuid, $product->uuid);

    $sourceWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->get($sourceWarehouseProduct, 100);

    $targetWarehouseProduct = $this->warehouseProductRepository
        ->get($targetWarehouse->uuid, $product->uuid);

    $targetWarehouseProductPrice = $this->warehouseProductPriceRepository
        ->get($targetWarehouseProduct, 100);


    expect($response->status())->toBeInt()->toBe(202)
        ->and($sourceWarehouseProduct->total_amount)->toBeFloat()->toBe(10.0)
        ->and($sourceWarehouseProductPrice->price)->toBeFloat()->toBe(100.0)
        ->and($sourceWarehouseProductPrice->amount)->toBeFloat()->toBe(10.0)
        ->and($targetWarehouseProduct->total_amount)->toBeFloat()->toBe(10.0)
        ->and($targetWarehouseProductPrice->price)->toBeFloat()->toBe(100.0)
        ->and($targetWarehouseProductPrice->amount)->toBeFloat()->toBe(10.0);
});

test('warehouse can get total amount of products through the api', function () {
    $products = Product::factory()->count(2)->create();

    /** @var Product $productOne */
    $productOne = $products->first();
    /** @var Product $productTwo */
    $productTwo = $products->last();

    $this->sourceWarehouse->receiveProduct($productOne->uuid, 100, 20);
    $this->sourceWarehouse->receiveProduct($productOne->uuid, 50, 20);
    $this->sourceWarehouse->receiveProduct($productTwo->uuid, 100, 30);
    $this->sourceWarehouse->receiveProduct($productTwo->uuid, 50, 30);

    $url = "/api/warehouses/{$this->sourceWarehouse->uuid}/products/total_amount";
    $response = $this->get($url);
    $data = $response->json()['data'];

    expect($data)->toBeArray()->toHaveCount(2)
        ->and($data[0])->toMatchArray([
            'product_name' => $productOne->name,
            'total_amount' => 40
        ])
        ->and($data[1])->toMatchArray([
            'product_name' => $productTwo->name,
            'total_amount' => 60
    ]);
});

test('csrf', function () {
    $response = $this->get('sanctum/csrf-cookie');

    dd($response);
});

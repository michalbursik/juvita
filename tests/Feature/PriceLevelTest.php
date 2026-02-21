<?php

use App\Models\User;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('can list price levels', function () {
    $product = Product::find(TestConstants::PRODUCT_APPLE_ID);
    $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);

    PriceLevel::create([
        'amount' => 10,
        'price' => 50,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
    ]);

    $response = $this->actingAs($this->user)->getJson('/api/priceLevels');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can filter price levels by warehouse', function () {
    $product = Product::find(TestConstants::PRODUCT_APPLE_ID);
    $warehouseMain = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
    $warehouseTrash = Warehouse::find(TestConstants::WAREHOUSE_TRASH_ID);

    PriceLevel::create([
        'amount' => 10,
        'price' => 50,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
        'product_id' => $product->id,
        'warehouse_id' => $warehouseMain->id,
    ]);

    PriceLevel::create([
        'amount' => 5,
        'price' => 40,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
        'product_id' => $product->id,
        'warehouse_id' => $warehouseTrash->id,
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/priceLevels?warehouse_id={$warehouseMain->id}");

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.warehouse_id', $warehouseMain->id);
});

<?php

use App\Models\Check;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
    $this->warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
    $this->product = Product::find(TestConstants::PRODUCT_APPLE_ID);

    // Initial stock
    $this->priceLevel = PriceLevel::create([
        'product_id' => $this->product->id,
        'warehouse_id' => $this->warehouse->id,
        'amount' => 10,
        'price' => 50,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
    ]);
    $this->warehouse->products()->updateExistingPivot($this->product->id, ['amount' => 10]);
});

test('can perform inventory check', function () {
    $response = $this->actingAs($this->user)->postJson('/api/warehouses/checks', [
        'warehouse_id' => $this->warehouse->id,
        'products' => [
            [
                'product_id' => $this->product->id,
                'price_level_id' => $this->priceLevel->id,
                'warehouse_id' => $this->warehouse->id,
                'amount' => 7,
            ],
        ],
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('checks', [
        'warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $this->assertDatabaseHas('movements', [
        'type' => 'check',
        'amount' => 3, // 10 - 7
        'product_id' => $this->product->id,
        'issue_warehouse_id' => $this->warehouse->id,
    ]);

    $this->assertEquals(7, $this->warehouse->products()->find($this->product->id)->product_warehouse->amount);
    $this->assertEquals(7, $this->priceLevel->fresh()->amount);
});

test('can list checks', function () {
    Check::create(['warehouse_id' => $this->warehouse->id, 'user_id' => $this->user->id, 'discount' => 0]);

    $response = $this->actingAs($this->user)->getJson('/api/warehouses/checks');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can fetch all products for check', function () {
    $response = $this->actingAs($this->user)->getJson("/api/warehouses/checks/products?warehouse_id={$this->warehouse->id}");

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data'); // Apple has amount 10
});

test('can show check', function () {
    $check = Check::create(['warehouse_id' => $this->warehouse->id, 'user_id' => $this->user->id, 'discount' => 0]);

    $response = $this->actingAs($this->user)->getJson("/api/warehouses/checks/{$check->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $check->id);
});

<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\PriceLevel;
use App\Models\Movement;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
    $this->warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
    $this->product = Product::find(TestConstants::PRODUCT_APPLE_ID);
});

test('can perform receipt movement', function () {
    $response = $this->actingAs($this->user)->postJson('/api/warehouses/movements/receipt', [
        'amount' => 10.5,
        'price' => 100,
        'product_id' => $this->product->id,
        'receipt_warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('movements', [
        'type' => 'receipt',
        'amount' => 10.5,
        'product_id' => $this->product->id,
        'receipt_warehouse_id' => $this->warehouse->id,
    ]);

    $this->assertDatabaseHas('price_levels', [
        'product_id' => $this->product->id,
        'warehouse_id' => $this->warehouse->id,
        'amount' => 10.5,
        'price' => 100,
    ]);

    $this->assertEquals(10.5, $this->warehouse->products()->find($this->product->id)->product_warehouse->amount);
});

test('can perform transmission movement', function () {
    $targetWarehouse = Warehouse::find(TestConstants::WAREHOUSE_TRASH_ID);

    // Initial stock via manual model creation since seeder starts with 0
    PriceLevel::create([
        'product_id' => $this->product->id,
        'warehouse_id' => $this->warehouse->id,
        'amount' => 20,
        'price' => 50,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
    ]);
    $this->warehouse->products()->updateExistingPivot($this->product->id, ['amount' => 20]);

    $priceLevel = $this->product->priceLevels()->where('warehouse_id', $this->warehouse->id)->first();

    $response = $this->actingAs($this->user)->postJson('/api/warehouses/movements/transmission', [
        'amount' => 5,
        'product_id' => $this->product->id,
        'issue_warehouse_id' => $this->warehouse->id,
        'receipt_warehouse_id' => $targetWarehouse->id,
        'price_level_id' => $priceLevel->id,
        'user_id' => $this->user->id,
    ]);

    $response->assertStatus(200);

    $this->assertEquals(15, $this->warehouse->products()->find($this->product->id)->product_warehouse->amount);
    $this->assertEquals(15, $priceLevel->fresh()->amount);
    $this->assertEquals(5, $targetWarehouse->products()->find($this->product->id)->product_warehouse->amount);

    $this->assertDatabaseHas('price_levels', [
        'product_id' => $this->product->id,
        'warehouse_id' => $targetWarehouse->id,
        'amount' => 5,
        'price' => 50,
    ]);
});

test('can list movements', function () {
    Movement::create([
        'type' => Movement::TYPE_RECEIPT,
        'amount' => 10,
        'price' => 100,
        'product_id' => $this->product->id,
        'receipt_warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->getJson('/api/movements');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can fetch all amounts', function () {
    Movement::create([
        'type' => Movement::TYPE_RECEIPT,
        'amount' => 10,
        'price' => 100,
        'product_id' => $this->product->id,
        'receipt_warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/movements/fetchAllAmounts?warehouse_id={$this->warehouse->id}");

    $response->assertStatus(200)
        ->assertJsonPath("data.{$this->product->id}.amount", 10);
});

test('can perform trash movement', function () {
    $trashWarehouse = Warehouse::find(TestConstants::WAREHOUSE_TRASH_ID);

    $this->warehouse->products()->updateExistingPivot($this->product->id, ['amount' => 20]);
    $priceLevel = PriceLevel::create([
        'product_id' => $this->product->id,
        'warehouse_id' => $this->warehouse->id,
        'amount' => 20,
        'price' => 50,
        'validFrom' => now(),
        'validTo' => now()->addWeek(),
        'status' => PriceLevel::STATUS_ACTIVE,
    ]);

    $response = $this->actingAs($this->user)->postJson('/api/warehouses/movements/trash', [
        'amount' => 5,
        'product_id' => $this->product->id,
        'issue_warehouse_id' => $this->warehouse->id,
        'price_level_id' => $priceLevel->id,
        'user_id' => $this->user->id,
    ]);

    $response->assertStatus(200);

    $this->assertEquals(15, $this->warehouse->products()->find($this->product->id)->product_warehouse->amount);
    $this->assertEquals(5, $trashWarehouse->products()->find($this->product->id)->product_warehouse->amount);
});

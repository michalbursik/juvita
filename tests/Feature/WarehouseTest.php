<?php

use App\Enums\WarehouseType;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('can list warehouses', function () {
    $response = $this->actingAs($this->user)->getJson('/api/warehouses');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data'); // Main and Trash
});

test('can create warehouse', function () {
    $response = $this->actingAs($this->user)->postJson('/api/warehouses', [
        'name' => 'New Warehouse',
        'type' => WarehouseType::MAIN->value,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('warehouses', ['name' => 'New Warehouse']);
});

test('can update warehouse', function () {
    $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);

    $response = $this->actingAs($this->user)->putJson("/api/warehouses/{$warehouse->id}", [
        'name' => 'Updated Name',
        'type' => WarehouseType::MAIN->value,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'name' => 'Updated Name']);
});

test('can delete warehouse', function () {
    $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);

    $response = $this->actingAs($this->user)->deleteJson("/api/warehouses/{$warehouse->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('warehouses', ['id' => $warehouse->id]);
});

test('can show warehouse', function () {
    $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);

    $response = $this->actingAs($this->user)->getJson("/api/warehouses/{$warehouse->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $warehouse->name);
});

test('can get trash warehouse', function () {
    $response = $this->actingAs($this->user)->getJson('/api/warehouses/trash');

    $response->assertStatus(200)
        ->assertJsonPath('data.id', TestConstants::WAREHOUSE_TRASH_ID);
});

test('can show warehouse product', function () {
    $response = $this->actingAs($this->user)->getJson('/api/warehouses/'.TestConstants::WAREHOUSE_MAIN_ID.'/products/'.TestConstants::PRODUCT_APPLE_ID);

    $response->assertStatus(200)
        ->assertJsonPath('data.id', TestConstants::PRODUCT_APPLE_ID);
});

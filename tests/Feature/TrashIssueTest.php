<?php

use App\Enums\WarehouseType;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('trash page does not throw SQL error when trash warehouse exists', function () {
    // Ensure trash warehouse exists
    Warehouse::firstOrCreate(
        ['type' => WarehouseType::TRASH],
        ['name' => 'Trash', 'id' => TestConstants::WAREHOUSE_TRASH_ID]
    );

    $response = $this->actingAs($this->user)->getJson('/api/warehouses/trash');

    $response->assertStatus(200);
});

test('trash page handles missing trash warehouse gracefully', function () {
    // Delete trash warehouse if it exists
    Warehouse::where('type', WarehouseType::TRASH)->delete();

    $response = $this->actingAs($this->user)->getJson('/api/warehouses/trash');

    // It should probably return 404 or an empty success, but not a 500 SQL error
    $response->assertStatus(200);
    $this->assertNull($response->json('data'));
});

test('movements index handles trash string as warehouse_id', function () {
    // This simulates what might be happening if the frontend sends "trash"
    $response = $this->actingAs($this->user)->getJson('/api/movements?receipt_warehouse_id=trash');

    // Currently this might fail with SQL error if not handled
    $response->assertStatus(200);
});

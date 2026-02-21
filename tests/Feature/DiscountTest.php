<?php

use App\Models\User;
use App\Models\Warehouse;
use App\Models\Discount;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
    $this->warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
});

test('can list discounts', function () {
    Discount::create([
        'amount' => 100,
        'note' => 'Test discount',
        'warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->getJson('/api/discounts');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can create discount', function () {
    $response = $this->actingAs($this->user)->postJson('/api/discounts', [
        'amount' => 50,
        'note' => 'New discount',
        'warehouse_id' => $this->warehouse->id,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('discounts', ['amount' => 50, 'note' => 'New discount']);
});

test('can update discount', function () {
    $discount = Discount::create([
        'amount' => 100,
        'note' => 'Test discount',
        'warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->putJson("/api/discounts/{$discount->id}", [
        'amount' => 150,
        'note' => 'Updated discount',
        'warehouse_id' => $this->warehouse->id,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('discounts', ['id' => $discount->id, 'amount' => 150]);
});

test('can delete discount', function () {
    $discount = Discount::create([
        'amount' => 100,
        'note' => 'Test discount',
        'warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->deleteJson("/api/discounts/{$discount->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('discounts', ['id' => $discount->id]);
});

test('can show discount', function () {
    $discount = Discount::create([
        'amount' => 100,
        'note' => 'Test discount',
        'warehouse_id' => $this->warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/discounts/{$discount->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.amount', 100);
});

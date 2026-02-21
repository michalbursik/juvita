<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('can list products', function () {
    $response = $this->actingAs($this->user)->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data'); // Apple and Banana
});

test('can create product', function () {
    $response = $this->actingAs($this->user)->postJson('/api/products', [
        'name' => 'Orange',
        'active' => true,
        'order' => 30,
        'unit' => 'kg',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('products', ['name' => 'Orange']);
});

test('can update product', function () {
    $product = Product::find(TestConstants::PRODUCT_APPLE_ID);

    $response = $this->actingAs($this->user)->putJson("/api/products/{$product->id}", [
        'name' => 'Apple Updated',
        'active' => true,
        'order' => 10,
        'unit' => 'kg',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Apple Updated']);
});

test('can delete product', function () {
    $product = Product::find(TestConstants::PRODUCT_APPLE_ID);

    // Manually detach from warehouses to avoid foreign key constraints in test
    $product->warehouses()->detach();

    $response = $this->actingAs($this->user)->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('can show next order', function () {
    $response = $this->actingAs($this->user)->getJson('/api/products/nextOrder');

    $response->assertStatus(200)
        ->assertJsonPath('data.order', 30); // Max is 20 (Banana) + 10
});

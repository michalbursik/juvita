<?php

use App\Models\User;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('can view overviews', function () {
    $product = Product::find(TestConstants::PRODUCT_APPLE_ID);
    $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);

    Movement::create([
        'type' => Movement::TYPE_RECEIPT,
        'amount' => 10,
        'price' => 50,
        'product_id' => $product->id,
        'receipt_warehouse_id' => $warehouse->id,
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->getJson('/api/overviews');

    $response->assertStatus(200);
    // Check if apple data is present
    $response->assertJsonPath("data.{$product->id}.product_name", 'Apple');
});

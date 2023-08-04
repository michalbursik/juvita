<?php

use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('can fetch detail of product', function () {
    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->get("api/products/$product->uuid");

    $response->assertStatus(200);

    $product->refresh();

    expect($response->json()['data'])
        ->toBeArray()
        ->toMatchArray([
        'uuid' => $product->uuid,
        'id' => $product->id,
        'name' => $product->name,
        "origin" => $product->origin,
        "active" => $product->active,
        "order" => $product->order,
        "unit" => $product->unit,
        "image" => $product->image,
    ]);
});

<?php

use App\Models\User;
use Database\Seeders\TestConstants;

beforeEach(function () {
    $this->user = User::find(TestConstants::USER_ADMIN_ID);
});

test('can list users', function () {
    $response = $this->actingAs($this->user)->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data'); // Admin and Employee from seeder
});

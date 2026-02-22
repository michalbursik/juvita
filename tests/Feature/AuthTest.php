<?php

test('user can login and get token', function () {
    $response = $this->postJson('/login', [
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'success',
            'data' => [
                'access_token',
            ],
        ]);
});

test('login fails with invalid credentials', function () {
    $response = $this->postJson('/login', [
        'email' => 'admin@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
});

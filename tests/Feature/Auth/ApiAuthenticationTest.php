<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(RefreshDatabase::class, DatabaseMigrations::class);

test('api login with valid credentials returns token', function () {
    // Create test user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Test login with kiosk client
    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
        'client' => 'kiosk',
        'device_name' => 'test-device'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'type',
            'client',
            'user' => [
                'id',
                'name',
                'email'
            ]
        ])
        ->assertJson([
            'type' => 'Bearer',
            'client' => 'kiosk',
            'user' => [
                'email' => 'test@example.com'
            ]
        ]);

    // Check that token is not empty
    expect($response->json('token'))->not->toBeEmpty();
});

test('api login with invalid credentials returns error', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword',
        'client' => 'kiosk',
        'device_name' => 'test-device'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Invalid credentials'
        ]);
});

test('api login with invalid client returns error', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
        'client' => 'invalid-client',
        'device_name' => 'test-device'
    ]);

    $response->assertStatus(422);
});

test('api login requires all fields', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        // missing password and client
    ]);

    $response->assertStatus(422);
});

test('api login works for all platforms', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $platforms = ['kiosk', 'android', 'ios', 'website'];

    foreach ($platforms as $platform) {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'client' => $platform,
            'device_name' => "test-{$platform}-device"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'type' => 'Bearer',
                'client' => $platform,
            ]);

        // Each platform should get a different token
        expect($response->json('token'))->not->toBeEmpty();
    }
});

test('api token can be used to access protected endpoints', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Login and get token
    $loginResponse = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
        'client' => 'kiosk',
        'device_name' => 'test-device'
    ]);

    $token = $loginResponse->json('token');

    // Use token to access protected endpoint
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ])->getJson('/api/kiosk/spots');

    // Should not get 401 unauthorized
    $response->assertStatus(200);
});
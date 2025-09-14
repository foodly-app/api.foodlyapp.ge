<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('user can get access token', function () {
    // Use existing user or create if doesn't exist
    $user = User::firstOrCreate(
        ['email' => 'gakhokia.david@gmail.com'],
        [
            'password' => bcrypt('Paroli_321!'),
            'name' => 'David Gakhokia'
        ]
    );

    // User sends login request
    $response = $this->postJson('/api/auth/login', [
        'email' => 'gakhokia.david@gmail.com',
        'password' => 'Paroli_321!',
        'client' => 'kiosk'
    ]);

    // Server returns access token
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
        ]);

    // Check response has token
    $responseData = $response->json();
    expect($responseData['token'])->not()->toBeEmpty();
    expect($responseData['type'])->toBe('Bearer');
    expect($responseData['client'])->toBe('kiosk');
    expect($responseData['user']['email'])->toBe('gakhokia.david@gmail.com');
    
    // Test protected endpoint with token
    $token = $responseData['token'];
    
    $protectedResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/kiosk/spots');
    
    $protectedResponse->assertStatus(200);
    
    // Output token for manual testing
    echo "\n🔑 Generated Token: " . $token . "\n";
    echo "📧 User Email: " . $responseData['user']['email'] . "\n";
    echo "👤 User Name: " . $responseData['user']['name'] . "\n";
    echo "📱 Client: " . $responseData['client'] . "\n";
});
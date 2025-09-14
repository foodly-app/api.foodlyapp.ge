<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request, string $client)
    {
        $data = $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // განსაზღვრავს ability-ს (kiosk, android, ios)
        $allowedClients = ['kiosk', 'android', 'ios'];
        if (! in_array($client, $allowedClients)) {
            return response()->json(['message' => 'Invalid client type'], 400);
        }

        $token = $user->createToken(
            $data['device_name'] ?? "{$client}-device",
            [$client]
        )->plainTextToken;

        return response()->json([
            'token' => $token,
            'type'  => 'Bearer',
            'client' => $client,
        ]);
    }

    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json(['message' => 'Logged out']);
    // }
}

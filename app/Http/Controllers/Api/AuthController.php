<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'client'      => ['required', 'string', 'in:kiosk,android,ios,website'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $client = $data['client'];
        $token = $user->createToken(
            $data['device_name'] ?? "{$client}-device",
            [$client]
        )->plainTextToken;

        return response()->json([
            'token' => $token,
            'type'  => 'Bearer',
            'client' => $client,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'client'      => ['required', 'string', 'in:kiosk,android,ios,website'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $client = $data['client'];
        $token = $user->createToken(
            $data['device_name'] ?? "{$client}-device",
            [$client]
        )->plainTextToken;

        return response()->json([
            'token' => $token,
            'type'  => 'Bearer',
            'client' => $client,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $currentToken = $request->user()->currentAccessToken();
        
        // Get client ability from current token
        $abilities = $currentToken->abilities;
        $client = $abilities[0] ?? 'kiosk';
        
        // Delete current token
        $user->tokens()->delete();
        
        // Create new token
        $newToken = $user->createToken(
            $request->input('device_name') ?? "{$client}-device",
            [$client]
        )->plainTextToken;

        return response()->json([
            'token' => $newToken,
            'type'  => 'Bearer',
            'client' => $client,
        ]);
    }
}

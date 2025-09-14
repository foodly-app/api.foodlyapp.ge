<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Kiosk\RestaurantController;

// 🔓 Public (login)
Route::post('/auth/login', fn($request) => app(AuthController::class)->login($request, 'kiosk'))
    ->middleware('throttle:auth');

// 🔒 Protected (require Sanctum + kiosk ability)
Route::middleware(['auth:sanctum', 'ability:kiosk'])->group(function () {
    Route::get('restaurants', [RestaurantController::class, 'index']);

});

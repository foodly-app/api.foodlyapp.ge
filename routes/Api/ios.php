<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Ios\RestaurantController;
use App\Http\Controllers\Api\Ios\SpotController;

// Public test endpoint (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('ios.test');
    Route::get('/spots/test', [SpotController::class, 'test'])->name('ios.spots.test');
});

// Protected routes (require authentication)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('restaurants')
        ->name('restaurants.')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
    
    Route::prefix('spots')
        ->name('spots.')
        ->controller(SpotController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
});
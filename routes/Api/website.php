<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpotController;

// Public test endpoint (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('website.test');
    // Route::get('/spots/test', [SpotController::class, 'test'])->name('website.spots.test');
});

// Protected routes (require authentication)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('restaurants')
        ->name('restaurants.')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
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
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpotController;

// Public routes (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('website.test');
    Route::get('/spots/test', [SpotController::class, 'test'])->name('website.spots.test');
    
    Route::prefix('restaurants')
        ->name('website.restaurants.')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });
    
    Route::prefix('spots')
        ->name('website.spots.')
        ->controller(SpotController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpot')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpot')->name('restaurants.top10');
        });
});

// Protected routes (require authentication)
// Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
//     // Future authenticated routes can go here
// });
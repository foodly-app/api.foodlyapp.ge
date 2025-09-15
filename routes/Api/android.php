<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Android\RestaurantController;
use App\Http\Controllers\Api\Android\SpotController;
use App\Http\Controllers\Api\Android\SpaceController;

// Public test endpoint (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('android.test');
    Route::get('/spots/test', [SpotController::class, 'test'])->name('android.spots.test');
    Route::get('/spaces/test', [SpaceController::class, 'test'])->name('android.spaces.test');
});

// Protected routes (require authentication)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('restaurants')
        ->name('android.restaurants.')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
    
    Route::prefix('spots')
        ->name('android.spots.')
        ->controller(SpotController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpot')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpot')->name('restaurants.top10');
        });
    
    Route::prefix('spaces')
        ->name('android.spaces.')
        ->controller(SpaceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpace')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpace')->name('restaurants.top10');
        });
});
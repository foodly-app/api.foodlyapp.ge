<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpotController;
use App\Http\Controllers\Api\Website\SpaceController;
use App\Http\Controllers\Api\Website\CuisineController;

// Public routes (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('website.test');
    Route::get('/spots/test', [SpotController::class, 'test'])->name('website.spots.test');
    Route::get('/spaces/test', [SpaceController::class, 'test'])->name('website.spaces.test');
    
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
    
    Route::prefix('spaces')
        ->name('website.spaces.')
        ->controller(SpaceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpace')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpace')->name('restaurants.top10');
        });
    
    Route::prefix('cuisines')
        ->name('website.cuisines.')
        ->controller(CuisineController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsByCuisine')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByCuisine')->name('restaurants.top10');
        });
});

// Protected routes (require authentication)
// Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
//     // Future authenticated routes can go here
// });
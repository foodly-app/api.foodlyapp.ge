<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpotController;
use App\Http\Controllers\Api\Website\SpaceController;
use App\Http\Controllers\Api\Website\CuisineController;
use App\Http\Controllers\Api\Website\DishController;
use App\Http\Controllers\Api\Website\CityController;

// Public routes (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/test', [RestaurantController::class, 'test'])->name('website.test');
    Route::get('/spots/test', [SpotController::class, 'test'])->name('website.spots.test');
    Route::get('/spaces/test', [SpaceController::class, 'test'])->name('website.spaces.test');

    Route::prefix('restaurants')
        ->name('website.restaurants.')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // აჩვენებს ყველა რესტორანს
            Route::get('/{slug}', 'showBySlug')->name('show'); // აჩვენებს კონკრეტულ რესტორანს slug-ით
            Route::get('/{slug}/details', 'showDetails')->name('details'); // დეტალები კონკრეტული რესტორნისთვის
            
            // Places
            Route::get('/{slug}/places', 'showByPlaces')->name('places'); // ადგილები კონკრეტული რესტორნისთვის
            Route::get('/{slug}/place/{place}', 'showByPlace')->name('place.show'); // კონკრეტული ადგილის დეტალები

            Route::get('/{slug}/place/{place}/tables', 'showTablesInPlace')->name('place.tables');
            Route::get('/{slug}/place/{place}/table/{table}', 'showTableInPlace')->name('place.table.show');
            Route::get('/{slug}/{place}/{table}', 'showTableInPlace')->name('place.table.show.short');

            // Tables
            Route::get('/{slug}/tables', 'showByTables')->name('tables'); // მაგიდები კონკრეტული რესტორნისთვის
            Route::get('/{slug}/table/{table}', 'showTable')->name('table.show'); // მაგიდის დეტალები კონკრეტული რესტორნისთვის
            
            // Menu
            Route::get('/{slug}/menu/categories', 'menuCategories')->name('menu.categories'); // მენიუ კატეგორები კონკრეტული რესტორნისთვის
            Route::get('/{slug}/menu/items', 'menuItems')->name('menu.items'); // მენიუ ელემენტები კონკრეტული რესტორნისთვის
            Route::get('/{slug}/menu', 'showMenu')->name('menu'); // მენიუ კონკრეტული რესტორნისთვის
            Route::get('/{slug}/full-menu', 'showFullMenu')->name('full-menu'); // სრული მენიუ კონკრეტული რესტორნისთვის
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


    Route::prefix('dishes')
        ->name('website.dishes.')
        ->controller(DishController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsByDish')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByDish')->name('restaurants.top10');
        });

    Route::prefix('cities')
        ->name('website.cities.')
        ->controller(CityController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsByCity')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByCity')->name('restaurants.top10');
        });
});

// Protected routes (require authentication)
// Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
//     // Future authenticated routes can go here
// });
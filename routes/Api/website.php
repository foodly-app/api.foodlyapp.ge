<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpotController;
use App\Http\Controllers\Api\Website\SpaceController;
use App\Http\Controllers\Api\Website\CuisineController;
use App\Http\Controllers\Api\Website\DishController;
use App\Http\Controllers\Api\Website\MenuCategoryController;
use App\Http\Controllers\Api\Website\MenuItemController;
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
            
            // Menu - ჰიერარქიული სტრუქტურა
            Route::get('/{slug}/menu', 'showMenu')->name('menu'); // რესტორნის სრული მენიუ სტრუქტურა
            Route::get('/{slug}/menu/categories', 'menuCategories')->name('menu.categories'); // რესტორნის მენიუ კატეგორიების სია
            Route::get('/{slug}/menu/category/{categorySlug}', 'menuCategory')->name('menu.category'); // კონკრეტული კატეგორია
            Route::get('/{slug}/menu/category/{categorySlug}/items', 'menuCategoryItems')->name('menu.category.items'); // კატეგორიის მენიუ ელემენტები
            Route::get('/{slug}/menu/item/{itemSlug}', 'menuItem')->name('menu.item'); // კონკრეტული მენიუ ელემენტი
            Route::get('/{slug}/menu/items', 'menuItems')->name('menu.items'); // ყველა მენიუ ელემენტი რესტორნისთვის
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

    Route::prefix('menu-categories')
        ->name('website.menu-categories.')
        ->controller(MenuCategoryController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // ყველა მენიუ კატეგორია
            Route::get('/hierarchy', 'getHierarchy')->name('hierarchy'); // ჰიერარქიული სტრუქტურა (ხე)
            Route::get('/{id}', 'show')->name('show'); // კონკრეტული კატეგორია ID-ით
            Route::get('/slug/{slug}', 'showBySlug')->name('show.slug'); // კონკრეტული კატეგორია slug-ით
            Route::get('/{id}/children', 'getChildren')->name('children'); // კატეგორიის შვილები
            Route::get('/{id}/breadcrumb', 'getBreadcrumb')->name('breadcrumb'); // ნავიგაციის გზა
            Route::get('/restaurant/{slug}', 'getByRestaurant')->name('restaurant'); // რესტორნის კატეგორიები
            Route::get('/restaurant/{slug}/items', 'getRestaurantCategoryItems')->name('restaurant.items'); // რესტორნის კატეგორიები მენიუ ელემენტებით
        });

    Route::prefix('menu-items')
        ->name('website.menu-items.')
        ->controller(MenuItemController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // ყველა მენიუ ელემენტი
            Route::get('/featured', 'getFeatured')->name('featured'); // რჩეული/პოპულარული მენიუ ელემენტები
            Route::get('/discounted', 'getDiscounted')->name('discounted'); // ფასდაკლებული მენიუ ელემენტები
            Route::get('/vegan', 'getVegan')->name('vegan'); // ვეგანური მენიუ ელემენტები
            Route::get('/gluten-free', 'getGlutenFree')->name('gluten-free'); // გლუტენის გარეშე მენიუ ელემენტები
            Route::get('/{id}', 'show')->name('show'); // კონკრეტული მენიუ ელემენტი ID-ით
            Route::get('/slug/{slug}', 'showBySlug')->name('show.slug'); // კონკრეტული მენიუ ელემენტი slug-ით
            Route::get('/restaurant/{slug}', 'getByRestaurant')->name('restaurant'); // რესტორნის მენიუ ელემენტები
            Route::get('/category/{slug}', 'getByCategory')->name('category'); // კატეგორიის მენიუ ელემენტები
        });
});

// Protected routes (require authentication)
// Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
//     // Future authenticated routes can go here
// });
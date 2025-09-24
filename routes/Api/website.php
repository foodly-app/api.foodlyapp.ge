<?php

use App\Http\Controllers\Api\Website\CityController;
use App\Http\Controllers\Api\Website\CuisineController;
use App\Http\Controllers\Api\Website\DishController;
use App\Http\Controllers\Api\Website\MenuCategoryController;
use App\Http\Controllers\Api\Website\MenuItemController;
use App\Http\Controllers\Api\Website\PlaceController;
use App\Http\Controllers\Api\Website\ReservationController;
use App\Http\Controllers\Api\Website\RestaurantController;
use App\Http\Controllers\Api\Website\SpaceController;
use App\Http\Controllers\Api\Website\SpotController;
use App\Http\Controllers\Api\Website\TableController;
use App\Http\Controllers\Api\Website\UserController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

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
            Route::get('/restaurant/{slug}', 'getByRestaurant')->name('restaurant'); // რესტორნის კატეგორები
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

    Route::prefix('places')
        ->name('website.places.')
        ->controller(PlaceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // ყველა ადგილი
            Route::get('/featured', 'getFeatured')->name('featured'); // რჩეული ადგილები
            Route::get('/search', 'search')->name('search'); // ადგილების ძიება
            Route::get('/{id}', 'show')->name('show'); // კონკრეტული ადგილი ID-ით
            Route::get('/slug/{slug}', 'showBySlug')->name('show.slug'); // კონკრეტული ადგილი slug-ით
            Route::get('/restaurant/{restaurantId}', 'getByRestaurant')->name('restaurant'); // რესტორნის ადგილები
            Route::get('/restaurant/slug/{restaurantSlug}', 'getByRestaurantSlug')->name('restaurant.slug'); // რესტორნის ადგილები slug-ით
        });

    Route::prefix('reservations')
        ->name('website.reservations.')
        ->controller(ReservationController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // რეზერვაციების სია
            Route::get('/{id}', 'show')->name('show')->where('id', '[0-9]+'); // კონკრეტული რეზერვაცია ID-ით (მხოლოდ რიცხვები)
            // შეზღუდული ძებნა ელექტრონული ფოსტით (email უნდა იყოს url-encoded)
            Route::get('/email/{email}', 'getByEmail')->name('by.email');
        });

    Route::prefix('tables')
        ->name('website.tables.')
        ->controller(TableController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // ყველა მაგიდა
            Route::get('/available', 'getAvailable')->name('available'); // ხელმისაწვდომი მაგიდები
            Route::get('/search', 'search')->name('search'); // მაგიდების ძიება
            Route::get('/{id}', 'show')->name('show'); // კონკრეტული მაგიდა ID-ით
            Route::get('/slug/{slug}', 'showBySlug')->name('show.slug'); // კონკრეტული მაგიდა slug-ით
            Route::get('/restaurant/{restaurantId}', 'getByRestaurant')->name('restaurant'); // რესტორნის მაგიდები
            Route::get('/restaurant/slug/{restaurantSlug}', 'getByRestaurantSlug')->name('restaurant.slug'); // რესტორნის მაგიდები slug-ით
            Route::get('/place/{placeId}', 'getByPlace')->name('place'); // ადგილის მაგიდები
        });

    Route::prefix('users')
        ->name('website.users.')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // მომხმარებლების სია
            Route::get('/{id}', 'show')->name('show')->where('id', '[0-9]+'); // კონკრეტული მომხმარებელი ID-ით (მხოლოდ რიცხვები)
        });

});

// Website authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\Website\AuthController::class, 'register'])->name('website.auth.register');
    Route::post('/login', [\App\Http\Controllers\Api\Website\AuthController::class, 'login'])->name('website.auth.login');
    Route::post('/forgot-password', [\App\Http\Controllers\Api\Website\AuthController::class, 'forgotPassword'])->name('website.auth.forgot');
    Route::post('/reset-password', [\App\Http\Controllers\Api\Website\AuthController::class, 'resetPassword'])->name('website.auth.reset');
    Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\Api\Website\AuthController::class, 'logout'])->name('website.auth.logout');
});

// Protected routes (require authentication)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('users')
        ->name('website.users.')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/profile', 'profile')->name('profile'); // მომხმარებლის პროფილი
            Route::post('/profile/update', 'updateProfile')->name('profile.update'); // პროფილის განახლება
            Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar'); // ავატარის განახლება
            Route::delete('/{id}', 'destroy')->name('destroy'); // მომხმარებლის წაშლა
        });
});

# Cuisines Module Implementation Guide

## Overview
The Cuisines module handles cuisine-based restaurant discovery, allowing users to explore restaurants by culinary styles (Italian, Georgian, Asian, Mediterranean, etc.). This module follows the same architecture pattern as Spots and Spaces modules.

## Purpose
- **Primary Function**: Enable restaurant discovery by cuisine type
- **Business Value**: Help users find restaurants based on their culinary preferences
- **User Experience**: Categorize restaurants by cooking styles and food traditions

## Architecture

### Model Structure
**Location**: `app/Models/Cuisine/Cuisine.php`

```php
class Cuisine extends Model
{
    use Translatable;
    
    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    // Translatable attributes
    public array $translatedAttributes = ['name'];
    
    // Relationships
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_cuisine');
    }
}
```

**Key Features**:
- Translatable `name` attribute for multi-language support
- Status management (active/inactive)
- Many-to-many relationship with restaurants via `restaurant_cuisine` pivot table
- Auto-generated slugs for SEO-friendly URLs

### Resource Structure
**Location**: `app/Http/Resources/CuisineResource.php`

```php
class CuisineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // Automatically localized
            'slug' => $this->slug,
            'status' => $this->status,
        ];
    }
}
```

## Controller Implementation

### Multi-Platform Support
Each platform has its own controller with identical methods but different middleware and locale handling:

#### Platform Controllers:
- **Kiosk**: `app/Http/Controllers/Api/Kiosk/CuisineController.php` (Georgian locale)
- **Android**: `app/Http/Controllers/Api/Android/CuisineController.php` (English locale)
- **iOS**: `app/Http/Controllers/Api/Ios/CuisineController.php` (Russian locale)
- **Website**: `app/Http/Controllers/Api/Website/CuisineController.php` (Multi-locale)

### Standard Methods

#### 1. `index()` - List All Active Cuisines
```php
public function index()
{
    $cuisines = Cuisine::where('status', Cuisine::STATUS_ACTIVE)
                      ->orderBy('name')
                      ->get();
    
    return CuisineResource::collection($cuisines);
}
```

**Endpoint**: `GET /api/{platform}/cuisines`
**Response**: Array of cuisine objects with localized names

#### 2. `showBySlug($slug)` - Get Cuisine Details
```php
public function showBySlug($slug)
{
    $cuisine = Cuisine::where('slug', $slug)
                     ->where('status', Cuisine::STATUS_ACTIVE)
                     ->firstOrFail();
    
    return new CuisineResource($cuisine);
}
```

**Endpoint**: `GET /api/{platform}/cuisines/{slug}`
**Response**: Single cuisine object with details

#### 3. `restaurantsByCuisine($slug)` - Get Restaurants by Cuisine
```php
public function restaurantsByCuisine($slug)
{
    $cuisine = Cuisine::where('slug', $slug)
                     ->where('status', Cuisine::STATUS_ACTIVE)
                     ->firstOrFail();
    
    $restaurants = $cuisine->restaurants()
                          ->where('restaurants.status', Restaurant::STATUS_ACTIVE)
                          ->orderBy('restaurants.name')
                          ->get();
    
    return RestaurantShortResource::collection($restaurants);
}
```

**Endpoint**: `GET /api/{platform}/cuisines/{slug}/restaurants`
**Response**: Array of restaurants serving that cuisine type

#### 4. `top10RestaurantsByCuisine($slug)` - Get Top 10 Restaurants
```php
public function top10RestaurantsByCuisine($slug)
{
    $cuisine = Cuisine::where('slug', $slug)
                     ->where('status', Cuisine::STATUS_ACTIVE)
                     ->firstOrFail();
    
    $restaurants = $cuisine->restaurants()
                          ->where('restaurants.status', Restaurant::STATUS_ACTIVE)
                          ->orderBy('restaurants.rating', 'desc')
                          ->limit(10)
                          ->get();
    
    return RestaurantShortResource::collection($restaurants);
}
```

**Endpoint**: `GET /api/{platform}/cuisines/{slug}/restaurants/top10`
**Response**: Top 10 highest-rated restaurants for that cuisine

## Route Configuration

### Platform-Specific Routes
Each platform has its own route file with the cuisine endpoints:

#### Kiosk Routes (`routes/Api/kiosk.php`)
```php
Route::prefix('cuisines')
    ->name('kiosk.cuisines.')
    ->controller(CuisineController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/{slug}/restaurants', 'restaurantsByCuisine')->name('restaurants');
        Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByCuisine')->name('restaurants.top10');
    });
```

#### Similar structure for Android, iOS, and Website routes with respective naming conventions.

## Database Relations

### Pivot Table: `restaurant_cuisine`
```sql
CREATE TABLE restaurant_cuisine (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id BIGINT NOT NULL,
    cuisine_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (cuisine_id) REFERENCES cuisines(id) ON DELETE CASCADE,
    UNIQUE KEY unique_restaurant_cuisine (restaurant_id, cuisine_id)
);
```

### Translations Table: `cuisine_translations`
```sql
CREATE TABLE cuisine_translations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    cuisine_id BIGINT NOT NULL,
    locale VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (cuisine_id) REFERENCES cuisines(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cuisine_locale (cuisine_id, locale)
);
```

## API Endpoints Summary

### Kiosk (Georgian - ka)
- `GET /api/kiosk/cuisines` - List cuisines
- `GET /api/kiosk/cuisines/{slug}` - Cuisine details
- `GET /api/kiosk/cuisines/{slug}/restaurants` - Restaurants by cuisine
- `GET /api/kiosk/cuisines/{slug}/restaurants/top10` - Top 10 restaurants

### Android (English - en)
- `GET /api/android/cuisines` - List cuisines
- `GET /api/android/cuisines/{slug}` - Cuisine details
- `GET /api/android/cuisines/{slug}/restaurants` - Restaurants by cuisine
- `GET /api/android/cuisines/{slug}/restaurants/top10` - Top 10 restaurants

### iOS (Russian - ru)
- `GET /api/ios/cuisines` - List cuisines
- `GET /api/ios/cuisines/{slug}` - Cuisine details
- `GET /api/ios/cuisines/{slug}/restaurants` - Restaurants by cuisine
- `GET /api/ios/cuisines/{slug}/restaurants/top10` - Top 10 restaurants

### Website (Multi-locale: ka, en, ru, tr)
- `GET /api/website/cuisines` - List cuisines
- `GET /api/website/cuisines/{slug}` - Cuisine details
- `GET /api/website/cuisines/{slug}/restaurants` - Restaurants by cuisine
- `GET /api/website/cuisines/{slug}/restaurants/top10` - Top 10 restaurants

## Example Cuisine Types
- **Italian** - Pizza, pasta, risotto restaurants
- **Georgian** - Traditional Georgian cuisine (khachapuri, khinkali, etc.)
- **Asian** - Chinese, Japanese, Thai, Korean cuisines
- **Mediterranean** - Greek, Turkish, Middle Eastern
- **European** - French, German, Spanish cuisines
- **Fast Food** - Burgers, sandwiches, quick service
- **Vegetarian/Vegan** - Plant-based cuisine options
- **Seafood** - Fish and seafood specialties

## Error Handling
- **404 Response**: When cuisine slug is not found or inactive
- **422 Response**: When validation fails for request parameters
- **500 Response**: For server errors during processing

## Localization Notes
- Cuisine names are automatically translated based on request locale
- Slug generation uses transliteration for non-Latin characters
- Default fallback to English if translation is missing

## Integration with Restaurant Discovery
The Cuisines module works alongside Spots and Spaces modules to provide comprehensive restaurant discovery options:
- **Spots**: Location-based discovery (city districts, neighborhoods)
- **Spaces**: Ambiance-based discovery (rooftop, outdoor, garden)
- **Cuisines**: Food-type-based discovery (Italian, Georgian, Asian)

Users can combine filters across modules for precise restaurant matching (e.g., "Italian restaurants in Vake with outdoor seating").

## Testing Endpoints
Each controller includes a `test()` method for development verification:
- `GET /api/{platform}/cuisines/test` - Returns platform and locale information

## See Also
- [Spots Module Documentation](../spots/SPOTS-MODULE-INSTRUCTIONS.md)
- [Spaces Module Documentation](../spaces/SPACES-MODULE-INSTRUCTIONS.md)
- [Module Architecture Overview](../README.md)
# 🎯 FOODLY API - Complete Spots Module Instructions

## 📋 Table of Contents
1. [Module Overview](#module-overview)
2. [Architecture & Structure](#architecture--structure)
3. [Data Model](#data-model)
4. [API Endpoints](#api-endpoints)
5. [Platform Differences](#platform-differences)
6. [Authentication & Authorization](#authentication--authorization)
7. [Request/Response Examples](#requestresponse-examples)
8. [Error Handling](#error-handling)
9. [Testing with Postman](#testing-with-postman)
10. [Database Structure](#database-structure)
11. [Implementation Guide](#implementation-guide)

---

## 📖 Module Overview

The **Spots Module** provides location-based restaurant discovery functionality across four platforms:
- **🏢 Kiosk** (Georgian) - Restaurant terminal systems
- **📱 Android** (English) - Mobile Android application
- **🍎 iOS** (Russian) - iOS mobile application
- **🌐 Website** (Multi-locale) - Web application

### Key Features
✅ **Multi-platform support** with consistent API structure  
✅ **Internationalization** with translatable spot names  
✅ **Restaurant associations** with ranking and status management  
✅ **Slug-based routing** for SEO-friendly URLs  
✅ **Pagination support** for large datasets  
✅ **Status management** (active, inactive, maintenance)  
✅ **Top restaurants** feature for each spot  

---

## 🏗 Architecture & Structure

### File Structure
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── Kiosk/SpotController.php      # Kiosk platform controller
│   │   ├── Android/SpotController.php    # Android platform controller
│   │   ├── Ios/SpotController.php        # iOS platform controller
│   │   └── Website/SpotController.php    # Website platform controller
│   └── Resources/
│       ├── Spot/SpotResource.php         # Spot API response formatting
│       └── Restaurant/RestaurantShortResource.php  # Restaurant response formatting
├── Models/
│   └── Spot/
│       ├── Spot.php                      # Main Spot model
│       └── SpotTranslation.php           # Translation model (auto-generated)
└── Http/Middleware/
    └── SetLocale.php                     # Locale management middleware

routes/Api/
├── kiosk.php                             # Kiosk platform routes
├── android.php                           # Android platform routes
├── ios.php                               # iOS platform routes
└── website.php                           # Website platform routes
```

---

## 📊 Data Model

### Spot Model (`app/Models/Spot/Spot.php`)

```php
class Spot extends Model
{
    use Translatable;

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'slug',           // SEO-friendly URL identifier
        'rank',           // Display order ranking
        'image',          // Image file path
        'image_link',     // External image URL
        'status',         // Current status
    ];

    public $translatedAttributes = [
        'name',           // Spot name (translatable)
    ];

    // Relationships
    public function restaurants() {
        return $this->belongsToMany(Restaurant::class)
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}
```

### Database Tables

#### `spots` table
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `slug` | varchar(255) | Unique URL identifier |
| `rank` | integer | Display order |
| `image` | varchar(255) | Image file path |
| `image_link` | text | External image URL |
| `status` | enum | active/inactive/maintenance |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

#### `spot_translations` table
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `spot_id` | bigint | Foreign key to spots |
| `locale` | varchar(255) | Language code (en, ka, ru, tr) |
| `name` | varchar(255) | Translated spot name |

#### `restaurant_spot` (pivot table)
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `restaurant_id` | bigint | Foreign key to restaurants |
| `spot_id` | bigint | Foreign key to spots |
| `rank` | integer | Restaurant ranking within spot |
| `status` | enum | active/inactive |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

---

## 🚀 API Endpoints

### Endpoint Structure (All Platforms)

Replace `{platform}` with: `kiosk`, `android`, `ios`, or `website`

#### Public Endpoints (No Authentication)
```
GET /api/{platform}/spots/test           # Platform test endpoint
```

#### Protected Endpoints (Require Authentication)
```
GET /api/{platform}/spots                # List all active spots
GET /api/{platform}/spots/{slug}         # Get single spot by slug
GET /api/{platform}/spots/{slug}/restaurants     # Get all restaurants for spot
GET /api/{platform}/spots/{slug}/restaurants/top10  # Get top 10 restaurants for spot
```

### Specific Platform URLs

#### 🏢 Kiosk Platform (Georgian)
```
GET /api/kiosk/spots
GET /api/kiosk/spots/{slug}
GET /api/kiosk/spots/{slug}/restaurants
GET /api/kiosk/spots/{slug}/restaurants/top10
```

#### 📱 Android Platform (English)
```
GET /api/android/spots
GET /api/android/spots/{slug}
GET /api/android/spots/{slug}/restaurants
GET /api/android/spots/{slug}/restaurants/top10
```

#### 🍎 iOS Platform (Russian)
```
GET /api/ios/spots
GET /api/ios/spots/{slug}
GET /api/ios/spots/{slug}/restaurants
GET /api/ios/spots/{slug}/restaurants/top10
```

#### 🌐 Website Platform (Multi-locale)
```
GET /api/website/spots
GET /api/website/spots/{slug}
GET /api/website/spots/{slug}/restaurants
GET /api/website/spots/{slug}/restaurants/top10
```

---

## 🌍 Platform Differences

### Kiosk Platform
- **Language**: Georgian (ka)
- **Authentication**: Required for all spot endpoints
- **Response**: Georgian translations prioritized

### Android Platform  
- **Language**: English (en)
- **Authentication**: Required for all spot endpoints
- **Response**: English translations prioritized

### iOS Platform
- **Language**: Russian (ru)
- **Authentication**: Required for all spot endpoints
- **Response**: Russian translations prioritized

### Website Platform
- **Language**: Multi-locale support
- **Authentication**: **NOT REQUIRED** for spot endpoints (public access)
- **Response**: Supports locale parameter for dynamic language switching

---

## 🔐 Authentication & Authorization

### Authentication Required Platforms
- **Kiosk**: `auth:sanctum` middleware
- **Android**: `auth:sanctum` middleware  
- **iOS**: `auth:sanctum` middleware

### Public Access Platform
- **Website**: No authentication required

### Client Types for Login
```json
{
  "email": "user@example.com",
  "password": "password",
  "client": "kiosk|android|ios",
  "device_name": "Device Name"
}
```

---

## 📝 Request/Response Examples

### 1. List All Spots

**Request:**
```http
GET /api/kiosk/spots
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "slug": "tbilisi-center",
      "status": "active",
      "image": "/images/spots/tbilisi-center.jpg",
      "image_link": "https://example.com/image.jpg",
      "translations": [
        {
          "locale": "ka",
          "name": "თბილისის ცენტრი"
        },
        {
          "locale": "en", 
          "name": "Tbilisi Center"
        },
        {
          "locale": "ru",
          "name": "Центр Тбилиси"
        }
      ]
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "per_page": 12,
    "total": 25
  }
}
```

### 2. Get Single Spot by Slug

**Request:**
```http
GET /api/android/spots/tbilisi-center
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "slug": "tbilisi-center", 
    "status": "active",
    "rank": 1,
    "name": "Tbilisi Center",
    "image": "/images/spots/tbilisi-center.jpg",
    "image_link": "https://example.com/image.jpg"
  }
}
```

### 3. Get Restaurants by Spot

**Request:**
```http
GET /api/ios/spots/tbilisi-center/restaurants
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Restaurant Name",
      "slug": "restaurant-slug",
      "status": "active",
      "pivot": {
        "rank": 1,
        "status": "active"
      }
    }
  ]
}
```

### 4. Get Top 10 Restaurants

**Request:**
```http
GET /api/website/spots/tbilisi-center/restaurants/top10
Accept: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Top Restaurant",
      "slug": "top-restaurant",
      "status": "active",
      "pivot": {
        "rank": 1,
        "status": "active"
      }
    }
  ]
}
```

### 5. Website with Locale Parameter

**Request:**
```http
GET /api/website/spots?locale=ka
Accept: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "status": "active",
      "rank": 1,
      "slug": "tbilisi-center",
      "name": "თბილისის ცენტრი",
      "image": "/images/spots/tbilisi-center.jpg",
      "image_link": "https://example.com/image.jpg"
    }
  ]
}
```

---

## ⚠️ Error Handling

### Common Error Responses

#### 404 - Spot Not Found
```json
{
  "error": "Spot not found"
}
```

#### 404 - No Restaurants Found
```json
{
  "error": "No restaurants found for this spot"
}
```

#### 500 - Server Error
```json
{
  "error": "Failed to fetch spots",
  "message": "Detailed error message"
}
```

#### 401 - Unauthorized (Protected Platforms)
```json
{
  "message": "Unauthenticated."
}
```

---

## 🧪 Testing with Postman

### Collection Structure
The Postman collection includes complete testing for all platforms:

```
FOODLY API - Complete Collection
├── 🔐 Authentication
│   ├── Login User (Kiosk)
│   ├── Login User (Android) 
│   ├── Login User (iOS)
│   └── Logout User
├── 🏢 Kiosk Platform
│   └── 📍 Spots
│       ├── List All Spots
│       ├── Get Spot by Slug
│       ├── Get Restaurants by Spot
│       └── Get Top 10 Restaurants
├── 📱 Android Platform  
│   └── 📍 Spots
│       ├── List All Spots
│       ├── Get Spot by Slug
│       ├── Get Restaurants by Spot
│       └── Get Top 10 Restaurants
├── 🍎 iOS Platform
│   └── 📍 Spots
│       ├── List All Spots
│       ├── Get Spot by Slug
│       ├── Get Restaurants by Spot
│       └── Get Top 10 Restaurants
└── 🌐 Website Platform
    ├── 📍 Spots
    │   ├── List All Spots
    │   ├── Get Spot by Slug
    │   ├── Get Restaurants by Spot
    │   └── Get Top 10 Restaurants
    └── 🏪 Restaurants
        ├── List All Restaurants
        └── Get Restaurant by ID
```

### Environment Variables Required
```json
{
  "test_user_email": "user@example.com",
  "test_user_password": "password",
  "auth_token": "{{auth_token}}",
  "user_id": "{{user_id}}",
  "test_spot_slug": "test-spot-slug"
}
```

---

## 💾 Database Structure

### Migration Commands
```bash
# Create spots table
php artisan make:migration create_spots_table

# Create spot translations table  
php artisan make:migration create_spot_translations_table

# Create restaurant_spot pivot table
php artisan make:migration create_restaurant_spot_table
```

### Sample Data Structure
```sql
-- Spots
INSERT INTO spots (slug, rank, status, image, image_link) VALUES
('tbilisi-center', 1, 'active', '/images/spots/tbilisi-center.jpg', 'https://example.com/image.jpg'),
('batumi-boulevard', 2, 'active', '/images/spots/batumi-boulevard.jpg', null);

-- Spot Translations
INSERT INTO spot_translations (spot_id, locale, name) VALUES
(1, 'ka', 'თბილისის ცენტრი'),
(1, 'en', 'Tbilisi Center'),
(1, 'ru', 'Центр Тбилиси'),
(2, 'ka', 'ბათუმის ბულვარი'),
(2, 'en', 'Batumi Boulevard'),
(2, 'ru', 'Батумский бульвар');

-- Restaurant-Spot Associations
INSERT INTO restaurant_spot (restaurant_id, spot_id, rank, status) VALUES
(1, 1, 1, 'active'),
(2, 1, 2, 'active'),
(3, 2, 1, 'active');
```

---

## 🛠 Implementation Guide

### 1. Controller Implementation Pattern

All platform controllers follow the same structure:

```php
<?php

namespace App\Http\Controllers\Api\{Platform};

use App\Http\Controllers\Controller;
use App\Models\Spot\Spot;
use App\Http\Resources\Spot\SpotResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    // List active spots with pagination
    public function index(Request $request) { /* ... */ }
    
    // Get spot by slug
    public function showBySlug($slug) { /* ... */ }
    
    // Get restaurants for spot
    public function restaurantsBySpot($slug) { /* ... */ }
    
    // Get top 10 restaurants for spot
    public function top10RestaurantsBySpot($slug) { /* ... */ }
    
    // Test endpoint (no auth)
    public function test(Request $request) { /* ... */ }
}
```

### 2. Route Implementation Pattern

Each platform has its own route file:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Api\{Platform}\SpotController;

// Public test endpoints
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/spots/test', [SpotController::class, 'test']);
});

// Protected routes (auth required for kiosk/android/ios)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('spots')
        ->name('{platform}.spots.')
        ->controller(SpotController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpot')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpot')->name('restaurants.top10');
        });
});
```

### 3. Resource Implementation

SpotResource handles locale-specific responses:

```php
public function toArray($request)
{
    $locale = $request->query('locale');

    if ($locale) {
        app()->setLocale($locale);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'rank' => $this->rank,
            'slug' => $this->slug,
            'name' => $this->name,  // Auto-translated
            'image' => $this->image,
            'image_link' => $this->image_link,
        ];
    }

    return [
        'id' => $this->id,
        'slug' => $this->slug,
        'status' => $this->status,
        'image' => $this->image,
        'image_link' => $this->image_link,
        'translations' => $this->translations->map(function ($tr) {
            return [
                'locale' => $tr->locale,
                'name' => $tr->name,
            ];
        }),
    ];
}
```

### 4. Key Implementation Features

#### Status Management
- Only `active` spots are returned in listings
- Restaurant associations also check `active` status
- Supports `maintenance` mode for future use

#### Ranking System
- Spots ordered by `rank` (ascending)
- Restaurants within spots ordered by `rank` (ascending)
- Top 10 feature respects ranking

#### Error Handling
- Graceful `ModelNotFoundException` handling
- Descriptive error messages
- Proper HTTP status codes

#### Pagination
- 12 items per page by default
- Laravel pagination with meta information
- Links for navigation

---

## 📚 Additional Resources

### Related Documentation
- `docs/README.md` - Complete API documentation
- `docs/QUICK-REFERENCE.md` - API quick reference 
- `docs/POSTMAN-IMPORT-GUIDE.md` - Postman setup instructions
- `docs/Collection/` - Postman collection files

### Key Files to Review
- `app/Models/Spot/Spot.php` - Main data model
- `app/Http/Resources/Spot/SpotResource.php` - API response formatting  
- `routes/Api/*.php` - Platform-specific routes
- `app/Http/Controllers/Api/*/SpotController.php` - Platform controllers

---

## 🎯 Summary

The Spots Module provides a robust, multi-platform solution for location-based restaurant discovery with:

✅ **Consistent API** across all platforms  
✅ **Flexible authentication** (required vs public access)  
✅ **Multi-language support** with translations  
✅ **SEO-friendly** slug-based routing  
✅ **Comprehensive testing** with Postman collection  
✅ **Scalable architecture** for future enhancements  

The module is fully implemented and ready for production use across Kiosk, Android, iOS, and Website platforms.
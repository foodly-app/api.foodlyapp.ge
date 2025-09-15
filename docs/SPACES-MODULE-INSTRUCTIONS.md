# 🌌 FOODLY API - Complete Spaces Module Instructions

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

The **Spaces Module** provides location-based restaurant discovery functionality across four platforms:
- **🏢 Kiosk** (Georgian) - Restaurant terminal systems
- **📱 Android** (English) - Mobile Android application
- **🍎 iOS** (Russian) - iOS mobile application
- **🌐 Website** (Multi-locale) - Web application

### Key Features
✅ **Multi-platform support** with consistent API structure  
✅ **Internationalization** with translatable space names  
✅ **Restaurant associations** with ranking and status management  
✅ **Slug-based routing** for SEO-friendly URLs  
✅ **Pagination support** for large datasets  
✅ **Status management** (active, inactive, maintenance)  
✅ **Top restaurants** feature for each space  

---

## 🏗 Architecture & Structure

### File Structure
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── Kiosk/SpaceController.php      # Kiosk platform controller
│   │   ├── Android/SpaceController.php    # Android platform controller
│   │   ├── Ios/SpaceController.php        # iOS platform controller
│   │   └── Website/SpaceController.php    # Website platform controller
│   └── Resources/
│       ├── Space/SpaceResource.php        # Space API response formatting
│       └── Restaurant/RestaurantShortResource.php  # Restaurant response formatting
├── Models/
│   └── Space/
│       ├── Space.php                      # Main Space model
│       └── SpaceTranslation.php           # Translation model (auto-generated)
└── Http/Middleware/
    └── SetLocale.php                      # Locale management middleware

routes/Api/
├── kiosk.php                              # Kiosk platform routes
├── android.php                            # Android platform routes
├── ios.php                                # iOS platform routes
└── website.php                            # Website platform routes
```

---

## 📊 Data Model

### Space Model (`app/Models/Space/Space.php`)

```php
class Space extends Model
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
        'name',           // Space name (translatable)
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

#### `spaces` table
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

#### `space_translations` table
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `space_id` | bigint | Foreign key to spaces |
| `locale` | varchar(255) | Language code (en, ka, ru, tr) |
| `name` | varchar(255) | Translated space name |

#### `restaurant_space` (pivot table)
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `restaurant_id` | bigint | Foreign key to restaurants |
| `space_id` | bigint | Foreign key to spaces |
| `rank` | integer | Restaurant ranking within space |
| `status` | enum | active/inactive |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

---

## 🚀 API Endpoints

### Endpoint Structure (All Platforms)

Replace `{platform}` with: `kiosk`, `android`, `ios`, or `website`

#### Public Endpoints (No Authentication)
```
GET /api/{platform}/spaces/test           # Platform test endpoint
```

#### Protected Endpoints (Require Authentication)
```
GET /api/{platform}/spaces                # List all active spaces
GET /api/{platform}/spaces/{slug}         # Get single space by slug
GET /api/{platform}/spaces/{slug}/restaurants     # Get all restaurants for space
GET /api/{platform}/spaces/{slug}/restaurants/top10  # Get top 10 restaurants for space
```

### Specific Platform URLs

#### 🏢 Kiosk Platform (Georgian)
```
GET /api/kiosk/spaces
GET /api/kiosk/spaces/{slug}
GET /api/kiosk/spaces/{slug}/restaurants
GET /api/kiosk/spaces/{slug}/restaurants/top10
```

#### 📱 Android Platform (English)
```
GET /api/android/spaces
GET /api/android/spaces/{slug}
GET /api/android/spaces/{slug}/restaurants
GET /api/android/spaces/{slug}/restaurants/top10
```

#### 🍎 iOS Platform (Russian)
```
GET /api/ios/spaces
GET /api/ios/spaces/{slug}
GET /api/ios/spaces/{slug}/restaurants
GET /api/ios/spaces/{slug}/restaurants/top10
```

#### 🌐 Website Platform (Multi-locale)
```
GET /api/website/spaces
GET /api/website/spaces/{slug}
GET /api/website/spaces/{slug}/restaurants
GET /api/website/spaces/{slug}/restaurants/top10
```

---

## 🌍 Platform Differences

### Kiosk Platform
- **Language**: Georgian (ka)
- **Authentication**: Required for all space endpoints
- **Response**: Georgian translations prioritized

### Android Platform  
- **Language**: English (en)
- **Authentication**: Required for all space endpoints
- **Response**: English translations prioritized

### iOS Platform
- **Language**: Russian (ru)
- **Authentication**: Required for all space endpoints
- **Response**: Russian translations prioritized

### Website Platform
- **Language**: Multi-locale support
- **Authentication**: **NOT REQUIRED** for space endpoints (public access)
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

### 1. List All Spaces

**Request:**
```http
GET /api/kiosk/spaces
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "slug": "outdoor-dining",
      "status": "active",
      "image": "/images/spaces/outdoor-dining.jpg",
      "image_link": "https://example.com/image.jpg",
      "translations": [
        {
          "locale": "ka",
          "name": "ღია ცის ქვეშ"
        },
        {
          "locale": "en", 
          "name": "Outdoor Dining"
        },
        {
          "locale": "ru",
          "name": "Открытое пространство"
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

### 2. Get Single Space by Slug

**Request:**
```http
GET /api/android/spaces/outdoor-dining
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "slug": "outdoor-dining", 
    "status": "active",
    "rank": 1,
    "name": "Outdoor Dining",
    "image": "/images/spaces/outdoor-dining.jpg",
    "image_link": "https://example.com/image.jpg"
  }
}
```

### 3. Get Restaurants by Space

**Request:**
```http
GET /api/ios/spaces/outdoor-dining/restaurants
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
GET /api/website/spaces/outdoor-dining/restaurants/top10
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
GET /api/website/spaces?locale=ka
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
      "slug": "outdoor-dining",
      "name": "ღია ცის ქვეშ",
      "image": "/images/spaces/outdoor-dining.jpg",
      "image_link": "https://example.com/image.jpg"
    }
  ]
}
```

---

## ⚠️ Error Handling

### Common Error Responses

#### 404 - Space Not Found
```json
{
  "error": "Space not found"
}
```

#### 404 - No Restaurants Found
```json
{
  "error": "No restaurants found for this space"
}
```

#### 500 - Server Error
```json
{
  "error": "Failed to fetch spaces",
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
The Postman collection should include complete testing for all platforms:

```
FOODLY API - Complete Collection
├── 🔐 Authentication
│   ├── Login User (Kiosk)
│   ├── Login User (Android) 
│   ├── Login User (iOS)
│   └── Logout User
├── 🏢 Kiosk Platform
│   ├── 📍 Spots
│   └── 🌌 Spaces
│       ├── List All Spaces
│       ├── Get Space by Slug
│       ├── Get Restaurants by Space
│       └── Get Top 10 Restaurants
├── 📱 Android Platform  
│   ├── 📍 Spots
│   └── 🌌 Spaces
│       ├── List All Spaces
│       ├── Get Space by Slug
│       ├── Get Restaurants by Space
│       └── Get Top 10 Restaurants
├── 🍎 iOS Platform
│   ├── 📍 Spots
│   └── 🌌 Spaces
│       ├── List All Spaces
│       ├── Get Space by Slug
│       ├── Get Restaurants by Space
│       └── Get Top 10 Restaurants
└── 🌐 Website Platform
    ├── 📍 Spots
    ├── 🌌 Spaces
    │   ├── List All Spaces
    │   ├── Get Space by Slug
    │   ├── Get Restaurants by Space
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
  "test_space_slug": "test-space-slug"
}
```

---

## 💾 Database Structure

### Sample Data Structure
```sql
-- Spaces
INSERT INTO spaces (slug, rank, status, image, image_link) VALUES
('outdoor-dining', 1, 'active', '/images/spaces/outdoor-dining.jpg', 'https://example.com/image.jpg'),
('rooftop-terrace', 2, 'active', '/images/spaces/rooftop-terrace.jpg', null),
('garden-view', 3, 'active', '/images/spaces/garden-view.jpg', null);

-- Space Translations
INSERT INTO space_translations (space_id, locale, name) VALUES
(1, 'ka', 'ღია ცის ქვეშ'),
(1, 'en', 'Outdoor Dining'),
(1, 'ru', 'Открытое пространство'),
(2, 'ka', 'სახურავის ტერასა'),
(2, 'en', 'Rooftop Terrace'),
(2, 'ru', 'Террasa на крыше'),
(3, 'ka', 'ბაღის ხედი'),
(3, 'en', 'Garden View'),
(3, 'ru', 'Вид на сад');

-- Restaurant-Space Associations
INSERT INTO restaurant_space (restaurant_id, space_id, rank, status) VALUES
(1, 1, 1, 'active'),
(2, 1, 2, 'active'),
(3, 2, 1, 'active'),
(4, 3, 1, 'active');
```

---

## 🛠 Implementation Guide

### 1. Controller Implementation Pattern

All platform controllers follow the same structure:

```php
<?php

namespace App\Http\Controllers\Api\{Platform};

use App\Http\Controllers\Controller;
use App\Models\Space\Space;
use App\Http\Resources\Space\SpaceResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    // List active spaces with pagination
    public function index(Request $request) { /* ... */ }
    
    // Get space by slug
    public function showBySlug($slug) { /* ... */ }
    
    // Get restaurants for space
    public function restaurantsBySpace($slug) { /* ... */ }
    
    // Get top 10 restaurants for space
    public function top10RestaurantsBySpace($slug) { /* ... */ }
    
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
use App\Http\Controllers\Api\{Platform}\SpaceController;

// Public test endpoints
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/spaces/test', [SpaceController::class, 'test']);
});

// Protected routes (auth required for kiosk/android/ios)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('spaces')
        ->name('{platform}.spaces.')
        ->controller(SpaceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{slug}', 'showBySlug')->name('showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBySpace')->name('restaurants');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBySpace')->name('restaurants.top10');
        });
});
```

### 3. Resource Implementation

SpaceResource handles locale-specific responses:

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
- Only `active` spaces are returned in listings
- Restaurant associations also check `active` status
- Supports `maintenance` mode for future use

#### Ranking System
- Spaces ordered by `rank` (ascending)
- Restaurants within spaces ordered by `rank` (ascending)
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
- `docs/SPOTS-MODULE-INSTRUCTIONS.md` - Similar module implementation
- `docs/POSTMAN-IMPORT-GUIDE.md` - Postman setup instructions
- `docs/Collection/` - Postman collection files

### Key Files to Review
- `app/Models/Space/Space.php` - Main data model
- `app/Http/Resources/Space/SpaceResource.php` - API response formatting  
- `routes/Api/*.php` - Platform-specific routes
- `app/Http/Controllers/Api/*/SpaceController.php` - Platform controllers

---

## 🎯 Summary

The Spaces Module provides a robust, multi-platform solution for location-based restaurant discovery with:

✅ **Consistent API** across all platforms  
✅ **Flexible authentication** (required vs public access)  
✅ **Multi-language support** with translations  
✅ **SEO-friendly** slug-based routing  
✅ **Comprehensive testing** capability  
✅ **Scalable architecture** for future enhancements  

The module is fully implemented and ready for production use across Kiosk, Android, iOS, and Website platforms, following the same proven pattern as the Spots module.
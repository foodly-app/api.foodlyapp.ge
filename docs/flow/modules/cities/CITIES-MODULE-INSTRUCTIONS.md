# 🏙️ FOODLY API - Complete Cities Module Instructions

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

The **Cities Module** provides city-based restaurant discovery functionality across four platforms:
- **🏢 Kiosk** (Georgian) - Restaurant terminal systems
- **📱 Android** (English) - Mobile Android application
- **🍎 iOS** (Russian) - iOS mobile application
- **🌐 Website** (Multi-locale) - Web application

### Key Features
✅ **Multi-platform support** with consistent API structure  
✅ **Internationalization** with translatable city names  
✅ **Restaurant associations** with ranking and status management  
✅ **Slug-based routing** for SEO-friendly URLs  
✅ **Pagination support** for large datasets  
✅ **Status management** (active, inactive, maintenance)  
✅ **Top restaurants** feature for each city  

---

## 🏗 Architecture & Structure

### File Structure
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   └── Website/CityController.php     # Website platform controller
│   └── Resources/
│       ├── City/
│       │   ├── CityResource.php           # City API response formatting
│       │   └── CityShortResource.php      # Short city response formatting
│       └── Restaurant/RestaurantShortResource.php  # Restaurant response formatting
├── Models/
│   └── City/
│       ├── City.php                       # Main City model
│       └── CityTranslation.php            # Translation model (auto-generated)
└── Http/Middleware/
    └── SetLocale.php                      # Locale management middleware

routes/Api/
└── website.php                            # Website platform routes (currently only platform)
```

---

## 📊 Data Model

### City Model (`app/Models/City/City.php`)

```php
class City extends Model
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
        'name',           // City name (translatable)
        'description',    // City description (translatable)
        'meta_title',     // SEO meta title (translatable)
        'meta_description', // SEO meta description (translatable)
        'meta_keywords',  // SEO meta keywords (translatable)
    ];

    // Helper methods
    public static function getStatuses(): array {
        return [
            static::STATUS_ACTIVE => 'Active',
            static::STATUS_INACTIVE => 'Inactive',
            static::STATUS_MAINTENANCE => 'Maintenance',
        ];
    }

    public function getStatusLabelAttribute(): string {
        return static::getStatuses()[$this->status] ?? 'Unknown';
    }

    // Relationships
    public function restaurants() {
        return $this->belongsToMany(Restaurant::class, 'city_restaurant')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}
```

### CityTranslation Model (`app/Models/City/CityTranslation.php`)

```php
class CityTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',           // City name in specific language
        'description',    // City description in specific language
        'meta_title',     // SEO meta title in specific language
        'meta_description', // SEO meta description in specific language
        'meta_keywords',  // SEO meta keywords in specific language
    ];
}
```

---

## 🛣 API Endpoints

### Website Platform Routes (`routes/Api/website.php`)

```php
Route::prefix('cities')
    ->name('website.cities.')
    ->controller(CityController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/{slug}/restaurants', 'restaurantsByCity')->name('restaurants');
        Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByCity')->name('restaurants.top10');
    });
```

### Endpoint Details

#### 1. List All Cities
- **URL**: `GET /api/website/cities`
- **Description**: Get paginated list of active cities
- **Pagination**: 12 items per page
- **Response**: Collection of CityResource

#### 2. Get Single City
- **URL**: `GET /api/website/cities/{slug}`
- **Description**: Get detailed city information by slug
- **Response**: Single CityResource

#### 3. Get Restaurants by City
- **URL**: `GET /api/website/cities/{slug}/restaurants`
- **Description**: Get all restaurants in this city
- **Response**: Collection of RestaurantShortResource

#### 4. Get Top 10 Restaurants by City
- **URL**: `GET /api/website/cities/{slug}/restaurants/top10`
- **Description**: Get top 10 restaurants in this city (by rank)
- **Response**: Collection of RestaurantShortResource (max 10)

---

## 🌍 Platform Differences

Currently, the Cities module is implemented only for the **Website platform**. Future implementations for other platforms should follow the same pattern as the Spots module:

### Future Platform Support:
- **🏢 Kiosk** - Georgian language, simplified interface
- **📱 Android** - English language, mobile-optimized
- **🍎 iOS** - Russian language, iOS-specific features

---

## 🔐 Authentication & Authorization

Currently, all city endpoints are **public** and do not require authentication.

```php
// Public routes (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    // All city routes are public
});
```

---

## 📝 Request/Response Examples

### 1. Get All Cities

**Request:**
```http
GET /api/website/cities?locale=ka
Content-Type: application/json
```

**Response (with locale):**
```json
{
  "data": [
    {
      "id": 1,
      "status": "active",
      "rank": 1,
      "slug": "tbilisi",
      "name": "თბილისი",
      "description": "საქართველოს დედაქალაქი",
      "meta_title": "თბილისი - საქართველოს დედაქალაქი",
      "meta_description": "აღმოაჩინეთ თბილისის საუკეთესო რესტორნები",
      "meta_keywords": "თბილისი, რესტორნები, საქართველო",
      "image": "/images/cities/tbilisi.jpg",
      "image_link": "https://example.com/tbilisi.jpg"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

**Response (without locale - all translations):**
```json
{
  "data": [
    {
      "id": 1,
      "slug": "tbilisi",
      "status": "active",
      "image": "/images/cities/tbilisi.jpg",
      "image_link": "https://example.com/tbilisi.jpg",
      "translations": [
        {
          "locale": "ka",
          "name": "თბილისი",
          "description": "საქართველოს დედაქალაქი",
          "meta_title": "თბილისი - საქართველოს დედაქალაქი",
          "meta_description": "აღმოაჩინეთ თბილისის საუკეთესო რესტორნები",
          "meta_keywords": "თბილისი, რესტორნები, საქართველო"
        },
        {
          "locale": "en",
          "name": "Tbilisi",
          "description": "Capital of Georgia",
          "meta_title": "Tbilisi - Capital of Georgia",
          "meta_description": "Discover the best restaurants in Tbilisi",
          "meta_keywords": "Tbilisi, restaurants, Georgia"
        },
        {
          "locale": "ru",
          "name": "Тбилиси",
          "description": "Столица Грузии",
          "meta_title": "Тбилиси - Столица Грузии",
          "meta_description": "Откройте лучшие рестораны Тбилиси",
          "meta_keywords": "Тбилиси, рестораны, Грузия"
        }
      ]
    }
  ]
}
```

### 2. Get Single City

**Request:**
```http
GET /api/website/cities/tbilisi?locale=en
Content-Type: application/json
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "status": "active",
    "rank": 1,
    "slug": "tbilisi",
    "name": "Tbilisi",
    "description": "Capital of Georgia",
    "meta_title": "Tbilisi - Capital of Georgia",
    "meta_description": "Discover the best restaurants in Tbilisi",
    "meta_keywords": "Tbilisi, restaurants, Georgia",
    "image": "/images/cities/tbilisi.jpg",
    "image_link": "https://example.com/tbilisi.jpg"
  }
}
```

### 3. Get Restaurants by City

**Request:**
```http
GET /api/website/cities/tbilisi/restaurants?locale=ka
Content-Type: application/json
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "ქართული რესტორანი",
      "slug": "georgian-restaurant",
      "status": "active",
      "rank": 1,
      "image": "/images/restaurants/georgian.jpg"
    }
  ]
}
```

---

## 🚨 Error Handling

### Standard Error Responses

#### 404 - City Not Found
```json
{
  "error": "City not found"
}
```

#### 404 - No Cities Found
```json
{
  "error": "No cities found"
}
```

#### 404 - No Restaurants Found
```json
{
  "error": "No restaurants found for this city"
}
```

#### 500 - Server Error
```json
{
  "error": "Failed to fetch cities",
  "message": "Detailed error message"
}
```

---

## 🧪 Testing with Postman

### Collection Setup
Import the FOODLY API collection and test these city endpoints:

1. **Get All Cities**
   - Method: GET
   - URL: `{{base_url}}/api/website/cities`
   - Query params: `locale=ka`

2. **Get Single City**
   - Method: GET
   - URL: `{{base_url}}/api/website/cities/{{city_slug}}`
   - Query params: `locale=en`

3. **Get Restaurants by City**
   - Method: GET
   - URL: `{{base_url}}/api/website/cities/{{city_slug}}/restaurants`

4. **Get Top 10 Restaurants**
   - Method: GET
   - URL: `{{base_url}}/api/website/cities/{{city_slug}}/restaurants/top10`

---

## 🗄 Database Structure

### Required Tables

#### 1. `cities` table
```sql
CREATE TABLE cities (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE NOT NULL,
    rank INT DEFAULT 0,
    image VARCHAR(255) NULL,
    image_link VARCHAR(255) NULL,
    status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 2. `city_translations` table
```sql
CREATE TABLE city_translations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    city_id BIGINT NOT NULL,
    locale VARCHAR(5) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_city_locale (city_id, locale)
);
```

#### 3. `city_restaurant` table (pivot)
```sql
CREATE TABLE city_restaurant (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    city_id BIGINT NOT NULL,
    restaurant_id BIGINT NOT NULL,
    rank INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_city_restaurant (city_id, restaurant_id)
);
```

---

## 📋 Implementation Guide

### Step 1: Model Setup
✅ Create `City` model with Translatable trait  
✅ Create `CityTranslation` model  
✅ Define relationships with Restaurant model  
✅ Add status constants and helper methods  

### Step 2: Controller Implementation
✅ Create `CityController` for Website platform  
✅ Implement CRUD operations with proper error handling  
✅ Add pagination support  
✅ Implement restaurant association endpoints  

### Step 3: Resource Classes
✅ Create `CityResource` for detailed responses  
✅ Create `CityShortResource` for list responses  
✅ Support locale-based and full translation responses  

### Step 4: Route Definition
✅ Define RESTful routes for city operations  
✅ Group routes with proper naming conventions  
✅ Apply necessary middleware (SetLocale)  

### Step 5: Testing
⏳ Create unit tests for model relationships  
⏳ Create feature tests for API endpoints  
⏳ Test with different locales  
⏳ Test error scenarios  

### Future Enhancements
⏳ Add Kiosk platform support  
⏳ Add Android platform support  
⏳ Add iOS platform support  
⏳ Add image upload functionality  
⏳ Add geographical coordinates  
⏳ Add weather integration  
⏳ Add city district/area support  

---

## 📚 Related Documentation

- [Spots Module](../spots/SPOTS-MODULE-INSTRUCTIONS.md)
- [Dishes Module](../dishes/DISHES-MODULE-INSTRUCTIONS.md)
- [Cuisines Module](../cuisines/CUISINES-MODULE-INSTRUCTIONS.md)
- [Spaces Module](../spaces/SPACES-MODULE-INSTRUCTIONS.md)
- [API Quick Reference](../../QUICK-REFERENCE.md)
- [Postman Collection Guide](../../POSTMAN-IMPORT-GUIDE.md)

---

**Last Updated**: September 15, 2025  
**Module Status**: ✅ Implemented (Website Platform Only)  
**Version**: 1.0.0
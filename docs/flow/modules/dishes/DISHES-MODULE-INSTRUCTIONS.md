# 🍽️ FOODLY API - Complete Dishes Module Instructions

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

The **Dishes Module** provides dish-based restaurant discovery functionality across four platforms:
- **🏢 Kiosk** (Georgian) - Restaurant terminal systems
- **📱 Android** (English) - Mobile Android application
- **🍎 iOS** (Russian) - iOS mobile application
- **🌐 Website** (Multi-locale) - Web application

### Key Features
✅ **Multi-platform support** with consistent API structure  
✅ **Internationalization** with translatable dish names and descriptions  
✅ **Restaurant associations** with ranking and status management  
✅ **Slug-based routing** for SEO-friendly URLs  
✅ **Pagination support** for large datasets  
✅ **Status management** (active, inactive, maintenance)  
✅ **Top restaurants** feature for each dish  
✅ **Rich descriptions** with detailed dish information  

---

## 🏗 Architecture & Structure

### File Structure
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   └── Website/DishController.php    # Website platform controller
│   └── Resources/
│       ├── Dish/
│       │   ├── DishResource.php          # Dish API response formatting
│       │   └── DishShortResource.php     # Short dish response formatting
│       └── Restaurant/RestaurantShortResource.php  # Restaurant response formatting
├── Models/
│   └── Dish/
│       ├── Dish.php                      # Main Dish model
│       └── DishTranslation.php           # Translation model (auto-generated)
└── Http/Middleware/
    └── SetLocale.php                     # Locale management middleware

routes/Api/
└── website.php                           # Website platform routes (currently only platform)
```

---

## 📊 Data Model

### Dish Model (`app/Models/Dish/Dish.php`)

```php
class Dish extends Model
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
        'name',           // Dish name (translatable)
        'description',    // Dish description (translatable)
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
        return $this->belongsToMany(Restaurant::class, 'restaurant_dish')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}
```

### DishTranslation Model (`app/Models/Dish/DishTranslation.php`)

```php
class DishTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',           // Dish name in specific language
        'description',    // Dish description in specific language
    ];
}
```

---

## 🛣 API Endpoints

### Website Platform Routes (`routes/Api/website.php`)

```php
Route::prefix('dishes')
    ->name('website.dishes.')
    ->controller(DishController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/{slug}/restaurants', 'restaurantsByDish')->name('restaurants');
        Route::get('/{slug}/restaurants/top10', 'top10RestaurantsByDish')->name('restaurants.top10');
    });
```

### Endpoint Details

#### 1. List All Dishes
- **URL**: `GET /api/website/dishes`
- **Description**: Get paginated list of active dishes
- **Pagination**: 12 items per page
- **Response**: Collection of DishResource

#### 2. Get Single Dish
- **URL**: `GET /api/website/dishes/{slug}`
- **Description**: Get detailed dish information by slug
- **Response**: Single DishResource

#### 3. Get Restaurants by Dish
- **URL**: `GET /api/website/dishes/{slug}/restaurants`
- **Description**: Get all restaurants serving this dish
- **Response**: Collection of RestaurantShortResource

#### 4. Get Top 10 Restaurants by Dish
- **URL**: `GET /api/website/dishes/{slug}/restaurants/top10`
- **Description**: Get top 10 restaurants serving this dish (by rank)
- **Response**: Collection of RestaurantShortResource (max 10)

---

## 🌍 Platform Differences

Currently, the Dishes module is implemented only for the **Website platform**. Future implementations for other platforms should follow the same pattern as the Spots module:

### Future Platform Support:
- **🏢 Kiosk** - Georgian language, simplified interface
- **📱 Android** - English language, mobile-optimized
- **🍎 iOS** - Russian language, iOS-specific features

---

## 🔐 Authentication & Authorization

Currently, all dish endpoints are **public** and do not require authentication.

```php
// Public routes (no authentication required)
Route::middleware([SetLocale::class])->group(function () {
    // All dish routes are public
});
```

---

## 📝 Request/Response Examples

### 1. Get All Dishes

**Request:**
```http
GET /api/website/dishes?locale=ka
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
      "slug": "khachapuri",
      "name": "ხაჭაპური",
      "description": "ტრადიციული ქართული კერძი ყველით",
      "image": "/images/dishes/khachapuri.jpg",
      "image_link": "https://example.com/khachapuri.jpg"
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
      "slug": "khachapuri",
      "status": "active",
      "image": "/images/dishes/khachapuri.jpg",
      "image_link": "https://example.com/khachapuri.jpg",
      "translations": [
        {
          "locale": "ka",
          "name": "ხაჭაპური",
          "description": "ტრადიციული ქართული კერძი ყველით"
        },
        {
          "locale": "en",
          "name": "Khachapuri",
          "description": "Traditional Georgian cheese bread"
        },
        {
          "locale": "ru",
          "name": "Хачапури",
          "description": "Традиционный грузинский хлеб с сыром"
        }
      ]
    }
  ]
}
```

### 2. Get Single Dish

**Request:**
```http
GET /api/website/dishes/khachapuri?locale=en
Content-Type: application/json
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "status": "active",
    "rank": 1,
    "slug": "khachapuri",
    "name": "Khachapuri",
    "description": "Traditional Georgian cheese bread",
    "image": "/images/dishes/khachapuri.jpg",
    "image_link": "https://example.com/khachapuri.jpg"
  }
}
```

### 3. Get Restaurants by Dish

**Request:**
```http
GET /api/website/dishes/khachapuri/restaurants?locale=ka
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

#### 404 - Dish Not Found
```json
{
  "error": "Dish not found"
}
```

#### 404 - No Dishes Found
```json
{
  "error": "No dishes found"
}
```

#### 404 - No Restaurants Found
```json
{
  "error": "No restaurants found for this dish"
}
```

#### 500 - Server Error
```json
{
  "error": "Failed to fetch dishes",
  "message": "Detailed error message"
}
```

---

## 🧪 Testing with Postman

### Collection Setup
Import the FOODLY API collection and test these dish endpoints:

1. **Get All Dishes**
   - Method: GET
   - URL: `{{base_url}}/api/website/dishes`
   - Query params: `locale=ka`

2. **Get Single Dish**
   - Method: GET
   - URL: `{{base_url}}/api/website/dishes/{{dish_slug}}`
   - Query params: `locale=en`

3. **Get Restaurants by Dish**
   - Method: GET
   - URL: `{{base_url}}/api/website/dishes/{{dish_slug}}/restaurants`

4. **Get Top 10 Restaurants**
   - Method: GET
   - URL: `{{base_url}}/api/website/dishes/{{dish_slug}}/restaurants/top10`

---

## 🗄 Database Structure

### Required Tables

#### 1. `dishes` table
```sql
CREATE TABLE dishes (
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

#### 2. `dish_translations` table
```sql
CREATE TABLE dish_translations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    dish_id BIGINT NOT NULL,
    locale VARCHAR(5) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_dish_locale (dish_id, locale)
);
```

#### 3. `restaurant_dish` table (pivot)
```sql
CREATE TABLE restaurant_dish (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id BIGINT NOT NULL,
    dish_id BIGINT NOT NULL,
    rank INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_restaurant_dish (restaurant_id, dish_id)
);
```

---

## 📋 Implementation Guide

### Step 1: Model Setup
✅ Create `Dish` model with Translatable trait  
✅ Create `DishTranslation` model  
✅ Define relationships with Restaurant model  
✅ Add status constants and helper methods  

### Step 2: Controller Implementation
✅ Create `DishController` for Website platform  
✅ Implement CRUD operations with proper error handling  
✅ Add pagination support  
✅ Implement restaurant association endpoints  

### Step 3: Resource Classes
✅ Create `DishResource` for detailed responses  
✅ Create `DishShortResource` for list responses  
✅ Support locale-based and full translation responses  

### Step 4: Route Definition
✅ Define RESTful routes for dish operations  
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
⏳ Add dish categorization  
⏳ Add nutrition information  
⏳ Add allergen information  

---

## 📚 Related Documentation

- [Spots Module](../spots/SPOTS-MODULE-INSTRUCTIONS.md)
- [Cuisines Module](../cuisines/CUISINES-MODULE-INSTRUCTIONS.md)
- [Spaces Module](../spaces/SPACES-MODULE-INSTRUCTIONS.md)
- [API Quick Reference](../../QUICK-REFERENCE.md)
- [Postman Collection Guide](../../POSTMAN-IMPORT-GUIDE.md)

---

**Last Updated**: September 15, 2025  
**Module Status**: ✅ Implemented (Website Platform Only)  
**Version**: 1.0.0
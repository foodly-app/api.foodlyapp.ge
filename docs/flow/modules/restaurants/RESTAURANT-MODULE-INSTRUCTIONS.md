# 🏪 Restaurant Module - Complete Implementation Guide

## 📋 Overview

The Restaurant module provides comprehensive functionality for managing restaurants in the Foodly API. This module supports multi-language content through Laravel Translatable package and provides complete CRUD operations and relationships with other modules.

## 🏗️ Architecture

### Model Structure
```
app/Models/Restaurant/
├── Restaurant.php              # Main restaurant model with Translatable trait
└── RestaurantTranslation.php   # Translation model for multilingual fields
```

### Controller Structure
```
app/Http/Controllers/Api/Website/
└── RestaurantController.php    # Website platform endpoints
```

### Resource Structure
```
app/Http/Resources/Restaurant/
├── RestaurantResource.php         # Full restaurant data
├── RestaurantShortResource.php    # Listing/summary data
└── RestaurantDetailsResource.php  # Detailed view data
```

## 🔧 Models

### Restaurant Model

**Location:** `app/Models/Restaurant/Restaurant.php`

**Traits Used:**
- `Translatable` (Astrotomic/Laravel-Translatable)
- `HasFactory` (Laravel Factory support)

**Translatable Fields:**
- `name` - Restaurant name
- `description` - Restaurant description  
- `address` - Restaurant address

**Fillable Fields:**
```php
'slug', 'status', 'rank', 'logo', 'image', 'video', 'phone', 'email', 
'whatsapp', 'website', 'discount_rate', 'latitude', 'longitude', 
'map_link', 'map_embed_link', 'time_zone', 'price_per_person', 
'price_currency', 'working_hours', 'delivery_time', 'reservation_type', 
'qr_code_image', 'qr_code_link', 'created_by', 'updated_by', 'version'
```

**Status Constants:**
- `STATUS_ACTIVE = 'active'`
- `STATUS_INACTIVE = 'inactive'` 
- `STATUS_PENDING = 'pending'`

**Relationships:**
- `cuisines()` - Many-to-many with Cuisine model
- `spots()` - Many-to-many with Spot model  
- `dishes()` - Many-to-many with Dish model
- `spaces()` - Many-to-many with Space model
- `cities()` - Many-to-many with City model
- `creator()` - Belongs to User (created_by)
- `updater()` - Belongs to User (updated_by)

**Helper Methods:**
- `getStatuses()` - Returns all available statuses
- `getStatusLabelAttribute()` - Returns status label
- `scopeActive()` - Query scope for active restaurants
- `scopeOrdered()` - Query scope ordered by rank

### RestaurantTranslation Model

**Location:** `app/Models/Restaurant/RestaurantTranslation.php`

**Purpose:** Stores translatable content for restaurants

**Fields:**
- `restaurant_id` - Foreign key to restaurants table
- `locale` - Language code (en, ka, ru, tr)
- `name` - Restaurant name in specific language
- `description` - Restaurant description in specific language
- `address` - Restaurant address in specific language

## 🌐 API Endpoints

### Base URL
```
/api/website/restaurants
```

### Available Endpoints

#### 1. Get All Restaurants
```http
GET /api/website/restaurants
```

**Query Parameters:**
- `search` - Search in restaurant name and description
- `category` - Filter by category slug
- `city` - Filter by city slug
- `sort` - Sorting (rank_asc, discount_rate_desc, etc.)
- `per_page` - Items per page (default: 20)
- `locale` - Language code for translations

**Response:** Collection of RestaurantShortResource

#### 2. Get Restaurant by Slug
```http
GET /api/website/restaurants/{slug}
```

**Response:** RestaurantResource

#### 3. Get Restaurant Details
```http
GET /api/website/restaurants/{slug}/details
```

**Response:** RestaurantResource with places and tables

#### 4. Get Restaurant Places
```http
GET /api/website/restaurants/{slug}/places
```

**Response:** Restaurant with places collection

#### 5. Get Specific Place
```http
GET /api/website/restaurants/{slug}/place/{place}
```

**Response:** Restaurant with specific place details

#### 6. Get Place Tables
```http
GET /api/website/restaurants/{slug}/place/{place}/tables
```

**Response:** Restaurant, place, and tables collection

#### 7. Get Specific Table in Place
```http
GET /api/website/restaurants/{slug}/place/{place}/table/{table}
```

**Response:** Restaurant, place, and specific table

#### 8. Get Restaurant Tables
```http
GET /api/website/restaurants/{slug}/tables
```

**Response:** Restaurant with tables collection

#### 9. Get Specific Table
```http
GET /api/website/restaurants/{slug}/table/{table}
```

**Response:** Restaurant with specific table

#### 10. Get Menu Categories
```http
GET /api/website/restaurants/{slug}/menu/categories
```

**Response:** Restaurant with menu categories

#### 11. Get Menu Items
```http
GET /api/website/restaurants/{slug}/menu/items
```

**Response:** Restaurant with menu items

#### 12. Get Menu
```http
GET /api/website/restaurants/{slug}/menu
```

**Response:** Restaurant with structured menu

#### 13. Get Full Menu
```http
GET /api/website/restaurants/{slug}/full-menu
```

**Response:** Restaurant with complete menu structure

## 📊 Resources

### RestaurantResource
Full restaurant data including all fields and translations.

**Usage:** Single restaurant detailed views

**Locale Support:** ✅ Returns localized fields when `?locale=` parameter provided

### RestaurantShortResource  
Essential restaurant data for listings and summaries.

**Usage:** Restaurant lists, search results, related restaurant displays

**Locale Support:** ✅ Returns localized fields when `?locale=` parameter provided

### RestaurantDetailsResource
Detailed restaurant data for complex views.

**Usage:** Restaurant detail pages, admin panels

**Locale Support:** ✅ Returns localized fields when `?locale=` parameter provided

## 🔗 Relationships

### Many-to-Many Relationships
All many-to-many relationships use pivot tables with additional fields:

```php
->withPivot(['rank', 'status'])
->withTimestamps()
```

**Pivot Tables:**
- `restaurant_cuisine` - Links restaurants to cuisines
- `restaurant_spot` - Links restaurants to spots  
- `restaurant_dish` - Links restaurants to dishes
- `restaurant_space` - Links restaurants to spaces
- `city_restaurant` - Links restaurants to cities

## 🌍 Internationalization

### Supported Languages
- **English (en)** - Default/fallback language
- **Georgian (ka)** - Primary language
- **Russian (ru)** - Secondary language  
- **Turkish (tr)** - Secondary language

### Translation Usage

**In Controllers:**
```php
// Set locale from request parameter
$locale = $request->query('locale');
if ($locale) {
    app()->setLocale($locale);
}
```

**In Resources:**
```php
// Returns localized content if locale set
'name' => $this->name,
'description' => $this->description,

// Returns all translations if no locale
'translations' => $this->translations->map(function ($tr) {
    return [
        'locale' => $tr->locale,
        'name' => $tr->name,
        'description' => $tr->description,
        'address' => $tr->address,
    ];
})
```

## 🎯 Usage Examples

### Basic Restaurant Listing
```javascript
// Get restaurants with pagination
fetch('/api/website/restaurants?per_page=10&locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Search Restaurants
```javascript
// Search Georgian restaurants
fetch('/api/website/restaurants?search=ქართული&locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Get Restaurant Details
```javascript
// Get specific restaurant
fetch('/api/website/restaurants/georgian-house?locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Get Restaurant Menu
```javascript
// Get restaurant menu structure
fetch('/api/website/restaurants/georgian-house/menu?locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

## 🚀 Implementation Status

### ✅ Completed Features
- [x] Restaurant model with Translatable trait
- [x] RestaurantTranslation model
- [x] Complete API Resources (Restaurant, RestaurantShort, RestaurantDetails)
- [x] Full Website controller with all endpoints
- [x] Comprehensive routing structure
- [x] Multi-language support
- [x] Status management system
- [x] Relationship definitions
- [x] Query scopes and helper methods

### 🔄 Future Enhancements
- [ ] Menu categories integration (when MenuCategory model ready)
- [ ] Menu items integration (when MenuItem model ready)  
- [ ] Places integration (when Place model ready)
- [ ] Tables integration (when Table model ready)
- [ ] Advanced filtering options
- [ ] Caching layer for performance
- [ ] Search optimization
- [ ] Analytics integration

## 📝 Notes

1. **Database Design:** The module follows the same database structure as the original admin.foodlyapp.ge repository
2. **Consistency:** All methods follow the same patterns used in Dish, Spot, Space, and City modules
3. **Performance:** Uses eager loading for relationships to minimize N+1 queries
4. **Error Handling:** Comprehensive error handling with appropriate HTTP status codes
5. **Validation:** Ready for integration with Laravel Form Request classes

## 🔧 Configuration

### Required Dependencies
```json
{
    "astrotomic/laravel-translatable": "^11.16"
}
```

### Database Tables
- `restaurants` - Main restaurants table
- `restaurant_translations` - Translation table
- Pivot tables for relationships (as defined above)

### Migration Notes
Ensure the following database fields exist in the restaurants table to match the old repository structure:
- All fillable fields listed in the model
- Proper foreign key constraints
- Indexes on commonly queried fields (slug, status, rank)

This completes the Restaurant module implementation following the same patterns and standards used throughout the Foodly API project.
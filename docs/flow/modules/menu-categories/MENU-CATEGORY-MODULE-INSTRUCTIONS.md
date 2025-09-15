# 📋 MenuCategory Module - Complete Implementation Guide

## 📋 Overview

The MenuCategory module provides comprehensive functionality for managing hierarchical menu categories in the Foodly API. This module supports multi-language content through Laravel Translatable package and provides a complete tree structure for restaurant menu organization.

## 🏗️ Architecture

### Model Structure
```
app/Models/MenuCategory/
├── MenuCategory.php              # Main menu category model with Translatable trait
└── MenuCategoryTranslation.php   # Translation model for multilingual fields
```

### Controller Structure
```
app/Http/Controllers/Api/Website/
└── MenuCategoryController.php    # Website platform endpoints
```

### Resource Structure
```
app/Http/Resources/MenuCategory/
├── MenuCategoryResource.php                # Full menu category data
└── RestaurantCategoryItemsResource.php     # Restaurant with categories and items
```

## 🔧 Models

### MenuCategory Model

**Location:** `app/Models/MenuCategory/MenuCategory.php`

**Traits Used:**
- `Translatable` (Astrotomic/Laravel-Translatable)
- `HasFactory` (Laravel Factory support)

**Translatable Fields:**
- `name` - Category name
- `description` - Category description

**Fillable Fields:**
```php
'restaurant_id', 'parent_id', 'dish_id', 'rank', 'slug', 'image', 
'image_link', 'icon', 'icon_link', 'status'
```

**Status Constants:**
- `STATUS_ACTIVE = 'active'`
- `STATUS_INACTIVE = 'inactive'`

**Relationships:**
- `restaurant()` - Belongs to Restaurant model
- `dish()` - Belongs to Dish model (optional)
- `parent()` - Self-referencing parent category
- `children()` - Self-referencing child categories
- `allChildren()` - Recursive children (nested tree)

**Helper Methods:**
- `getStatuses()` - Returns all available statuses
- `getStatusLabelAttribute()` - Returns status label
- `scopeActive()` - Query scope for active categories
- `scopeOrdered()` - Query scope ordered by rank
- `generateSlug()` - Creates URL-friendly slug from name
- `hasChildren()` - Check if category has subcategories
- `getBreadcrumbPath()` - Get navigation path array
- `getDepthLevel()` - Get hierarchy depth level

### MenuCategoryTranslation Model

**Location:** `app/Models/MenuCategory/MenuCategoryTranslation.php`

**Purpose:** Stores translatable content for menu categories

**Fields:**
- `menu_category_id` - Foreign key to menu_categories table
- `locale` - Language code (en, ka, ru, tr)
- `name` - Category name in specific language
- `description` - Category description in specific language

## 🌐 API Endpoints

### Base URL
```
/api/website/menu-categories
```

### Available Endpoints

#### 1. Get All Menu Categories
```http
GET /api/website/menu-categories
```

**Query Parameters:**
- `restaurant_id` - Filter by restaurant ID
- `restaurant_slug` - Filter by restaurant slug
- `parent_id` - Filter by parent category ID
- `root_only` - Boolean, show only root categories (no parent)
- `search` - Search in category name and description
- `sort` - Sorting (rank_asc, rank_desc, name_asc, name_desc, created_at_desc, created_at_asc)
- `per_page` - Items per page (default: 20)
- `locale` - Language code for translations

**Response:** Paginated collection of MenuCategoryResource

#### 2. Get Category Hierarchy (Tree Structure)
```http
GET /api/website/menu-categories/hierarchy
```

**Query Parameters:**
- `restaurant_id` - Filter by restaurant ID
- `restaurant_slug` - Filter by restaurant slug
- `locale` - Language code for translations

**Response:** Collection of root categories with nested children

#### 3. Get Menu Category by ID
```http
GET /api/website/menu-categories/{id}
```

**Response:** MenuCategoryResource

#### 4. Get Menu Category by Slug
```http
GET /api/website/menu-categories/slug/{slug}
```

**Response:** MenuCategoryResource

#### 5. Get Category Children
```http
GET /api/website/menu-categories/{id}/children
```

**Response:** Collection of child categories

#### 6. Get Category Breadcrumb
```http
GET /api/website/menu-categories/{id}/breadcrumb
```

**Response:** Navigation path and depth information
```json
{
  "breadcrumb": [
    {"id": 1, "name": "Beverages", "slug": "beverages"},
    {"id": 5, "name": "Hot Drinks", "slug": "hot-drinks"},
    {"id": 12, "name": "Coffee", "slug": "coffee"}
  ],
  "depth": 2
}
```

#### 7. Get Categories by Restaurant
```http
GET /api/website/menu-categories/restaurant/{restaurant_slug}
```

**Query Parameters:**
- `root_only` - Boolean, show only root categories
- `locale` - Language code for translations

**Response:** Collection of restaurant's categories

#### 8. Get Restaurant Categories with Items
```http
GET /api/website/menu-categories/restaurant/{restaurant_slug}/items
```

**Response:** RestaurantCategoryItemsResource with restaurant, categories, and menu items

## 📊 Resources

### MenuCategoryResource
Full menu category data including all fields, translations, and child categories.

**Usage:** Category detail views, admin panels, navigation menus

**Locale Support:** ✅ Returns localized fields when `?locale=` parameter provided

**Includes:**
- All category fields
- Status labels
- Child categories (when loaded)
- All translations (when no specific locale)

### RestaurantCategoryItemsResource
Restaurant data with its categories and menu items.

**Usage:** Restaurant menu display, category-item relationships

**Locale Support:** ✅ Returns localized fields when `?locale=` parameter provided

**Includes:**
- Restaurant short data
- Category information
- Menu items (when MenuItem model is available)

## 🌲 Hierarchical Structure

### Tree Navigation
Menu categories support unlimited nesting levels:

```
📋 Main Dishes
├── 🍕 Pizza
│   ├── 🧀 Margherita
│   └── 🍖 Meat Lovers
├── 🍝 Pasta
│   ├── 🥄 Spaghetti
│   └── 🍜 Penne
└── 🥗 Salads
    ├── 🥬 Green Salads
    └── 🍅 Mediterranean
```

### Helper Methods Usage

**Get Navigation Path:**
```php
$category = MenuCategory::find(12);
$breadcrumb = $category->getBreadcrumbPath();
// Returns: [Beverages, Hot Drinks, Coffee]
```

**Check Depth Level:**
```php
$category = MenuCategory::find(12);
$depth = $category->getDepthLevel();
// Returns: 2 (Coffee is 3rd level: 0-based indexing)
```

**Check for Children:**
```php
$category = MenuCategory::find(1);
if ($category->hasChildren()) {
    // Category has subcategories
}
```

## 🔗 Relationships

### Restaurant Integration
- Each category belongs to a specific restaurant
- Categories can be filtered by restaurant
- Restaurant can have unlimited categories

### Dish Integration
- Categories can optionally link to specific dishes
- Useful for featured item categories
- Flexible relationship for special menu sections

### Self-Referencing Hierarchy
- `parent()` - Direct parent category
- `children()` - Direct child categories (active only)
- `allChildren()` - Recursive children for full tree

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
    ];
})
```

### Slug Generation
Automatic slug generation with language priority:
1. English translation (en)
2. Fallback locale (ka)
3. First available translation
4. Unique fallback: `category-{uniqid}`

## 🎯 Usage Examples

### Basic Category Listing
```javascript
// Get all categories for a restaurant
fetch('/api/website/menu-categories?restaurant_slug=georgian-house&locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Category Tree Structure
```javascript
// Get hierarchical category structure
fetch('/api/website/menu-categories/hierarchy?restaurant_slug=georgian-house&locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Category Navigation
```javascript
// Get breadcrumb for navigation
fetch('/api/website/menu-categories/12/breadcrumb?locale=ka')
  .then(response => response.json())
  .then(data => {
    console.log(data.breadcrumb); // Navigation path
    console.log(data.depth);      // Hierarchy depth
  });
```

### Root Categories Only
```javascript
// Get only top-level categories
fetch('/api/website/menu-categories?restaurant_slug=georgian-house&root_only=true&locale=ka')
  .then(response => response.json())
  .then(data => console.log(data));
```

## 🚀 Implementation Status

### ✅ Completed Features
- [x] MenuCategory model with Translatable trait
- [x] MenuCategoryTranslation model
- [x] Complete API Resources (MenuCategory, RestaurantCategoryItems)
- [x] Full Website controller with 8 endpoints
- [x] Hierarchical category structure (parent-child)
- [x] Breadcrumb navigation support
- [x] Multi-language support
- [x] Status management system
- [x] Comprehensive routing structure
- [x] Search and filtering capabilities
- [x] Slug generation system

### 🔄 Future Enhancements
- [ ] Menu items integration (when MenuItem model ready)
- [ ] Category icons and images upload
- [ ] Advanced sorting options
- [ ] Category analytics and usage stats
- [ ] Bulk category operations
- [ ] Category templates for quick setup
- [ ] Import/Export functionality
- [ ] Category performance optimization

## 📝 Notes

1. **Hierarchical Design:** Supports unlimited nesting levels for complex menu structures
2. **Performance:** Uses eager loading and efficient queries for tree traversal
3. **Flexibility:** Optional dish relationship for featured items or special categories
4. **Consistency:** Follows the same patterns used in Restaurant, Dish, and other modules
5. **Future-Ready:** Prepared for MenuItem integration when that model is implemented

## 🔧 Configuration

### Required Dependencies
```json
{
    "astrotomic/laravel-translatable": "^11.16"
}
```

### Database Tables
- `menu_categories` - Main categories table
- `menu_category_translations` - Translation table
- Foreign key relationships to restaurants and dishes tables

### Migration Considerations
Ensure the following database structure:
- Proper indexes on commonly queried fields (restaurant_id, parent_id, slug, status, rank)
- Foreign key constraints for referential integrity
- Efficient tree traversal support

## 🎨 Frontend Integration

### Tree Display
```javascript
// Recursive function to display category tree
function displayCategoryTree(categories, level = 0) {
    categories.forEach(category => {
        console.log('  '.repeat(level) + category.name);
        if (category.children && category.children.length > 0) {
            displayCategoryTree(category.children, level + 1);
        }
    });
}
```

### Breadcrumb Navigation
```javascript
// Display breadcrumb navigation
function displayBreadcrumb(breadcrumb) {
    return breadcrumb.map(item => item.name).join(' > ');
}
```

This completes the MenuCategory module implementation with full hierarchical support, multi-language capabilities, and comprehensive API endpoints for modern restaurant menu management! 🚀
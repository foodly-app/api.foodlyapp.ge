# MenuItem Module Instructions

## Overview
The MenuItem module provides comprehensive management of restaurant menu items with multi-language support, dietary preferences, pricing, and hierarchical organization within menu categories.

## Module Architecture

### Models
- **MenuItem**: `app/Models/Menu/MenuItem.php`
- **MenuItemTranslation**: `app/Models/Menu/MenuItemTranslation.php`

### Resources
- **MenuItemResource**: `app/Http/Resources/MenuItem/MenuItemResource.php` (Full details)
- **MenuItemShortResource**: `app/Http/Resources/MenuItem/MenuItemShortResource.php` (Listing view)

### Controllers
- **MenuItemController**: `app/Http/Controllers/Api/Website/MenuItemController.php`

## API Endpoints

### Website Platform Routes

#### 1. List Menu Items
```
GET /api/website/menu/items
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `menu_category_id` (optional): Filter by menu category
- `vegan` (optional): Filter vegan items (true/false)
- `gluten_free` (optional): Filter gluten-free items (true/false)
- `on_discount` (optional): Filter discounted items (true/false)
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `sort_by` (optional): price_asc, price_desc, name_asc, name_desc, created_desc
- `page` (optional): Page number for pagination

**Response:** Paginated list of menu items using MenuItemResource

#### 2. Get Menu Item by Slug
```
GET /api/website/menu/item/{slug}
```
**Response:** Single menu item details using MenuItemResource

#### 3. Get Menu Items by Restaurant
```
GET /api/website/menu/restaurant/{restaurant_id}/items
```
**Parameters:** Same filtering options as list endpoint
**Response:** Menu items for specific restaurant

#### 4. Get Menu Items by Category
```
GET /api/website/menu/category/{category_id}/items
```
**Parameters:** Same filtering options as list endpoint
**Response:** Menu items for specific category

#### 5. Get Featured Menu Items
```
GET /api/website/menu/items/featured
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `limit` (optional): Number of items to return (default: 10)

**Response:** Featured menu items using MenuItemShortResource

#### 6. Get Discounted Menu Items
```
GET /api/website/menu/items/discounted
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `limit` (optional): Number of items to return (default: 10)

**Response:** Discounted menu items using MenuItemShortResource

#### 7. Get Vegan Menu Items
```
GET /api/website/menu/items/vegan
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `limit` (optional): Number of items to return (default: 10)

**Response:** Vegan menu items using MenuItemShortResource

#### 8. Get Gluten-Free Menu Items
```
GET /api/website/menu/items/gluten-free
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `limit` (optional): Number of items to return (default: 10)

**Response:** Gluten-free menu items using MenuItemShortResource

## Model Features

### MenuItem Model

#### Status Constants
```php
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
const STATUS_DRAFT = 2;
```

#### Unit Constants
```php
const UNIT_PIECE = 'piece';
const UNIT_GRAM = 'gram';
const UNIT_KG = 'kg';
const UNIT_LITER = 'liter';
const UNIT_ML = 'ml';
```

#### Key Fields
- `name` (translatable): Item name
- `description` (translatable): Item description
- `ingredients` (translatable): Item ingredients
- `allergens` (translatable): Allergen information
- `price`: Base price
- `discount_price`: Discounted price
- `unit`: Measurement unit
- `weight`: Item weight/volume
- `calories`: Calorie count
- `preparation_time`: Preparation time in minutes
- `is_vegan`: Vegan flag
- `is_gluten_free`: Gluten-free flag
- `is_spicy`: Spicy flag
- `is_featured`: Featured flag
- `status`: Item status

#### Relationships
```php
// Get restaurant
$menuItem->restaurant;

// Get menu category
$menuItem->menuCategory;
```

#### Query Scopes
```php
// Active items
MenuItem::active()->get();

// Available items (active with stock)
MenuItem::available()->get();

// Vegan items
MenuItem::vegan()->get();

// Gluten-free items
MenuItem::glutenFree()->get();

// Discounted items
MenuItem::withDiscount()->get();

// Featured items
MenuItem::featured()->get();

// By restaurant
MenuItem::byRestaurant($restaurantId)->get();

// By category
MenuItem::byCategory($categoryId)->get();
```

#### Accessor Methods
```php
// Get discount percentage
$discountPercentage = $menuItem->discount_percentage;

// Check if item has discount
$hasDiscount = $menuItem->has_discount;

// Check if item is available
$isAvailable = $menuItem->is_available;
```

## Integration with Menu System

### Navigation Flow
1. **Restaurant Details** → `/api/website/restaurants/{slug}`
2. **Menu Categories** → `/api/website/menu/restaurant/{id}/categories`
3. **Category Items** → `/api/website/menu/category/{id}/items`
4. **Item Details** → `/api/website/menu/item/{slug}`

### Filtering and Search
The module supports comprehensive filtering:
- **Dietary Preferences**: Vegan, gluten-free, spicy
- **Price Range**: Min/max price filtering
- **Discounts**: Items with active discounts
- **Status**: Active, available items
- **Restaurant/Category**: Hierarchical filtering

### Multi-language Support
All translatable fields automatically use the current locale:
- Georgian (`ka`)
- English (`en`)
- Russian (`ru`)
- Turkish (`tr`)

## Usage Examples

### Frontend Integration
```javascript
// Get menu items for a restaurant
const response = await fetch('/api/website/menu/restaurant/1/items?vegan=true&sort_by=price_asc');
const menuItems = await response.json();

// Get item details
const itemResponse = await fetch('/api/website/menu/item/grilled-salmon');
const item = await itemResponse.json();

// Get featured items
const featuredResponse = await fetch('/api/website/menu/items/featured?limit=5');
const featured = await featuredResponse.json();
```

### Mobile App Optimization
- **MenuItemShortResource**: Optimized for listing views
- **MenuItemResource**: Full details for item pages
- **Pagination**: Efficient loading of large menus
- **Filtering**: Quick dietary and price filters

### Backend Usage
```php
// Get active menu items for a restaurant
$items = MenuItem::byRestaurant($restaurantId)
    ->active()
    ->available()
    ->with(['translations', 'restaurant', 'menuCategory'])
    ->paginate(20);

// Get vegan items on discount
$veganDiscounts = MenuItem::vegan()
    ->withDiscount()
    ->active()
    ->get();

// Check item availability
if ($menuItem->is_available) {
    // Item can be ordered
}
```

## Error Handling

### Common Scenarios
- **404 Not Found**: Item slug doesn't exist or item is inactive
- **Validation Errors**: Invalid filter parameters
- **Empty Results**: No items match filter criteria

### Response Format
```json
{
    "message": "Menu item not found",
    "status": 404
}
```

## Performance Considerations

### Database Optimization
- **Eager Loading**: Translations and relationships are pre-loaded
- **Indexes**: Optimized queries on restaurant_id, menu_category_id, status
- **Pagination**: Large result sets are paginated

### Caching Strategy
- **Static Data**: Menu items can be cached with restaurant/category invalidation
- **Dynamic Filters**: Cache filtered results for popular combinations

## Testing

### Test Coverage
- Model relationships and scopes
- API endpoint responses
- Filter functionality
- Multi-language support
- Resource transformation

### Example Test Cases
```php
// Test vegan filtering
$this->get('/api/website/menu/items?vegan=true')
    ->assertOk()
    ->assertJsonStructure(['data', 'meta']);

// Test item details
$this->get("/api/website/menu/item/{$item->slug}")
    ->assertOk()
    ->assertJson(['data' => ['id' => $item->id]]);
```

## Related Documentation
- [Restaurant Module](../restaurants/RESTAURANT-MODULE-INSTRUCTIONS.md)
- [MenuCategory Module](../menu-categories/MENU-CATEGORY-MODULE-INSTRUCTIONS.md)
- [API Response Formats](../../api/API-RESPONSE-FORMATS.md)
- [Multi-language Support](../../i18n/TRANSLATION-GUIDE.md)
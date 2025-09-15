# Table Module Instructions

## Overview
The Table module provides comprehensive management of dining tables within restaurants and places. Tables represent specific seating arrangements where customers can make reservations and dine, supporting capacity management, location tracking, and multi-language descriptions.

## Module Architecture

### Models
- **Table**: `app/Models/Table.php`
- **TableTranslation**: `app/Models/TableTranslation.php`

### Resources
- **TableResource**: `app/Http/Resources/Table/TableResource.php` (Full details)
- **TableShortResource**: `app/Http/Resources/Table/TableShortResource.php` (Listing view)

### Controllers
- **TableController**: `app/Http/Controllers/Api/Website/TableController.php`

## API Endpoints

### Website Platform Routes

#### 1. List Tables
```
GET /api/website/tables
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `place_id` (optional): Filter by place
- `status` (optional): Filter by status (default: available only)
- `min_capacity` (optional): Minimum seating capacity
- `max_capacity` (optional): Maximum seating capacity
- `sort_by` (optional): rank_asc, rank_desc, capacity_asc, capacity_desc, name_asc, name_desc, created_desc
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response:** Paginated list of tables using TableShortResource

#### 2. Get Table by ID
```
GET /api/website/tables/{id}
```
**Response:** Single table details using TableResource

#### 3. Get Table by Slug
```
GET /api/website/tables/slug/{slug}
```
**Response:** Single table details using TableResource

#### 4. Get Tables by Restaurant ID
```
GET /api/website/tables/restaurant/{restaurantId}
```
**Parameters:**
- `place_id` (optional): Filter by specific place
- `min_capacity`, `max_capacity` (optional): Capacity filtering
- `sort_by` (optional): Sorting options
- `page`, `per_page` (optional): Pagination

**Response:** Tables for specific restaurant using TableShortResource

#### 5. Get Tables by Restaurant Slug
```
GET /api/website/tables/restaurant/slug/{restaurantSlug}
```
**Parameters:**
- `place_slug` (optional): Filter by place slug
- `min_capacity`, `max_capacity` (optional): Capacity filtering
- `sort_by` (optional): Sorting options
- `page`, `per_page` (optional): Pagination

**Response:** Tables for specific restaurant using TableShortResource

#### 6. Get Tables by Place
```
GET /api/website/tables/place/{placeId}
```
**Parameters:**
- `min_capacity`, `max_capacity` (optional): Capacity filtering
- `sort_by` (optional): Sorting options
- `page`, `per_page` (optional): Pagination

**Response:** Tables for specific place using TableShortResource

#### 7. Get Available Tables
```
GET /api/website/tables/available
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `place_id` (optional): Filter by place
- `min_capacity`, `max_capacity` (optional): Capacity filtering
- `limit` (optional): Number of items to return (default: 10)

**Response:** Available tables using TableShortResource

#### 8. Search Tables
```
GET /api/website/tables/search
```
**Parameters:**
- `search` (required): Search term for name and description
- `restaurant_id` (optional): Filter by restaurant
- `place_id` (optional): Filter by place
- `page`, `per_page` (optional): Pagination

**Response:** Search results using TableShortResource

## Integration with Restaurant System

### Hierarchical Navigation Flow
1. **Restaurant Details** → `/api/website/restaurants/{slug}`
2. **Restaurant Places** → `/api/website/restaurants/{slug}/places`
3. **Place Tables** → `/api/website/restaurants/{slug}/place/{place_slug}/tables`
4. **Table Details** → `/api/website/restaurants/{slug}/place/{place_slug}/table/{table_slug}`

### Direct Table Access
- **All Tables** → `/api/website/tables`
- **Restaurant Tables** → `/api/website/tables/restaurant/slug/{slug}`
- **Place Tables** → `/api/website/tables/place/{id}`

## Model Features

### Table Model

#### Status Constants
```php
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
const STATUS_RESERVED = 2;
const STATUS_MAINTENANCE = 3;
```

#### Key Fields
- `name` (translatable): Table name/identifier
- `description` (translatable): Table description
- `restaurant_id`: Associated restaurant
- `place_id`: Associated place/area
- `slug`: URL-friendly identifier
- `status`: Table status (active/inactive/reserved/maintenance)
- `seats`: Number of seats (legacy field)
- `capacity`: Seating capacity
- `rank`: Display order priority
- `image`: Table image path
- `image_link`: External image URL
- `icon`: Table icon/symbol
- `latitude`: GPS latitude coordinate
- `longitude`: GPS longitude coordinate
- `qr_code_image`: QR code image path
- `qr_code_link`: QR code external URL
- `qr_code_url`: QR code value

#### Relationships
```php
// Get restaurant
$table->restaurant;

// Get place
$table->place;
```

#### Query Scopes
```php
// Active tables
Table::active()->get();

// Available tables (not reserved/maintenance)
Table::available()->get();

// Tables by restaurant
Table::byRestaurant($restaurantId)->get();

// Tables by place
Table::byPlace($placeId)->get();

// Order by rank
Table::orderByRank('asc')->get();

// Filter by capacity
Table::byCapacity(2, 8)->get(); // min 2, max 8 seats
```

#### Accessor Methods
```php
// Check table status
$isActive = $table->is_active;
$isAvailable = $table->is_available;
$isReserved = $table->is_reserved;

// Get capacity (uses seats as fallback)
$capacity = $table->capacity;

// Get full image URL
$imageUrl = $table->full_image_url;

// Get full QR code URL
$qrCodeUrl = $table->full_qr_code_url;

// Get status label
$statusLabel = $table->status_label;
```

#### Utility Methods
```php
// Generate slug from name
$slug = $table->generateSlug();

// Get all available statuses
$statuses = Table::getStatuses();
```

## Use Cases

### Restaurant Management
- **Floor Plan Organization**: Tables organized by places/areas
- **Capacity Planning**: Different table sizes for various party sizes
- **Status Management**: Track table availability, reservations, maintenance
- **Location Tracking**: GPS coordinates for outdoor/complex layouts

### Customer Experience
- **Table Selection**: Browse available tables by capacity and location
- **QR Code Integration**: Quick access to menus and ordering
- **Visual Information**: Table photos and descriptions
- **Accessibility**: Filter tables by specific needs

### Operations
- **Reservation System**: Integration with booking platforms
- **Real-time Status**: Live updates on table availability
- **Maintenance Tracking**: Schedule cleaning and repairs
- **Performance Analytics**: Track table utilization

## Multi-language Support
All translatable fields automatically use the current locale:
- Georgian (`ka`)
- English (`en`)
- Russian (`ru`)
- Turkish (`tr`)

## Usage Examples

### Frontend Integration
```javascript
// Get available tables for a restaurant
const response = await fetch('/api/website/tables/restaurant/slug/restaurant-name?min_capacity=4&sort_by=capacity_asc');
const tables = await response.json();

// Get tables in a specific place
const placeResponse = await fetch('/api/website/restaurants/restaurant-slug/place/terrace/tables');
const placeTables = await placeResponse.json();

// Get table details
const tableResponse = await fetch('/api/website/restaurants/restaurant-slug/place/terrace/table/table-01');
const table = await tableResponse.json();

// Search tables
const searchResponse = await fetch('/api/website/tables/search?search=window&min_capacity=2');
const results = await searchResponse.json();
```

### Mobile App Optimization
- **TableShortResource**: Optimized for listing views
- **TableResource**: Full details for table pages
- **Capacity Filtering**: Quick filters for party size
- **Image Handling**: Automatic URL generation for table photos

### Backend Usage
```php
// Get available tables for a restaurant
$tables = Table::byRestaurant($restaurantId)
    ->available()
    ->byCapacity($minCapacity, $maxCapacity)
    ->orderByRank()
    ->with(['restaurant', 'place', 'translations'])
    ->paginate(15);

// Find table by slug in specific place
$table = Table::where('slug', $slug)
    ->byPlace($placeId)
    ->available()
    ->with(['restaurant', 'place', 'translations'])
    ->firstOrFail();

// Get tables with specific capacity
$largeTables = Table::byCapacity(6)
    ->available()
    ->get();
```

## Error Handling

### Common Scenarios
- **404 Not Found**: Table slug doesn't exist or table is inactive
- **Validation Errors**: Invalid capacity or search parameters
- **Empty Results**: No tables match filter criteria
- **Relationship Errors**: Restaurant or place not found

### Response Format
```json
{
    "message": "Table not found",
    "status": 404
}
```

## Performance Considerations

### Database Optimization
- **Eager Loading**: Restaurant, place, and translation data pre-loaded
- **Indexes**: Optimized queries on restaurant_id, place_id, status, capacity
- **Pagination**: Large result sets are paginated
- **Scopes**: Efficient filtering with database-level constraints

### Caching Strategy
- **Static Data**: Tables can be cached with restaurant/place invalidation
- **Availability**: Real-time status updates bypass cache
- **Images**: CDN integration for table photos and QR codes

## Future Enhancements

### Reservation Integration
```php
// Check table availability for specific time
$isAvailable = $table->isAvailableAt($dateTime, $duration);

// Get availability schedule
$schedule = $table->getAvailabilitySchedule($date);
```

### Advanced Features
- **Table Combinations**: Link tables for larger parties
- **Service Areas**: Assign waitstaff to specific tables
- **Equipment Tracking**: High chairs, wheelchair accessibility
- **Ambient Data**: Noise level, lighting preferences
- **Photo Galleries**: Multiple images per table

### Analytics
- **Utilization Reports**: Track table usage patterns
- **Revenue Analysis**: Performance by table and time
- **Customer Preferences**: Popular table characteristics

## Testing

### Test Coverage
- Model relationships and scopes
- API endpoint responses
- Capacity filtering functionality
- Multi-language support
- Resource transformation
- Restaurant/Place integration

### Example Test Cases
```php
// Test table listing with filters
$this->get('/api/website/tables?restaurant_id=1&min_capacity=4')
    ->assertOk()
    ->assertJsonStructure(['data', 'meta']);

// Test hierarchical navigation
$this->get("/api/website/restaurants/{$restaurant->slug}/place/{$place->slug}/tables")
    ->assertOk()
    ->assertJsonStructure(['data' => ['restaurant', 'place', 'tables']]);

// Test table details
$this->get("/api/website/tables/slug/{$table->slug}")
    ->assertOk()
    ->assertJson(['data' => ['id' => $table->id]]);

// Test capacity filtering
$this->get('/api/website/tables/available?min_capacity=4&max_capacity=6')
    ->assertOk()
    ->assertJsonStructure(['data']);
```

## Related Documentation
- [Restaurant Module](../restaurants/RESTAURANT-MODULE-INSTRUCTIONS.md)
- [Place Module](../places/PLACE-MODULE-INSTRUCTIONS.md)
- [Reservation System](../reservations/RESERVATION-MODULE-INSTRUCTIONS.md) *(Future)*
- [API Response Formats](../../api/API-RESPONSE-FORMATS.md)
- [Multi-language Support](../../i18n/TRANSLATION-GUIDE.md)
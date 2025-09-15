# Place Module Instructions

## Overview
The Place module provides management of specific locations or areas within restaurants. Places represent distinct zones within a restaurant where tables and seating arrangements can be organized, such as "Main Dining Hall", "Terrace", "VIP Section", etc.

## Module Architecture

### Models
- **Place**: `app/Models/Place.php`
- **PlaceTranslation**: `app/Models/PlaceTranslation.php`

### Resources
- **PlaceResource**: `app/Http/Resources/Place/PlaceResource.php` (Full details)
- **PlaceShortResource**: `app/Http/Resources/Place/PlaceShortResource.php` (Listing view)

### Controllers
- **PlaceController**: `app/Http/Controllers/Api/Website/PlaceController.php`

## API Endpoints

### Website Platform Routes

#### 1. List Places
```
GET /api/website/places
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `status` (optional): Filter by status (default: active only)
- `sort_by` (optional): rank_asc, rank_desc, name_asc, name_desc, created_desc
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response:** Paginated list of places using PlaceShortResource

#### 2. Get Place by ID
```
GET /api/website/places/{id}
```
**Response:** Single place details using PlaceResource

#### 3. Get Place by Slug
```
GET /api/website/places/slug/{slug}
```
**Response:** Single place details using PlaceResource

#### 4. Get Places by Restaurant ID
```
GET /api/website/places/restaurant/{restaurantId}
```
**Parameters:**
- `sort_by` (optional): rank_asc, rank_desc, name_asc, name_desc
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response:** Places for specific restaurant using PlaceShortResource

#### 5. Get Places by Restaurant Slug
```
GET /api/website/places/restaurant/slug/{restaurantSlug}
```
**Parameters:** Same as restaurant ID endpoint
**Response:** Places for specific restaurant using PlaceShortResource

#### 6. Get Featured Places
```
GET /api/website/places/featured
```
**Parameters:**
- `restaurant_id` (optional): Filter by restaurant
- `limit` (optional): Number of items to return (default: 10)

**Response:** Featured places using PlaceShortResource

#### 7. Search Places
```
GET /api/website/places/search
```
**Parameters:**
- `search` (required): Search term for name and description
- `restaurant_id` (optional): Filter by restaurant
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response:** Search results using PlaceShortResource

## Model Features

### Place Model

#### Status Constants
```php
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
```

#### Key Fields
- `name` (translatable): Place name
- `description` (translatable): Place description
- `restaurant_id`: Associated restaurant
- `slug`: URL-friendly identifier
- `image`: Place image path
- `image_link`: External image URL
- `rank`: Display order priority
- `status`: Active/inactive status
- `qr_code`: QR code value
- `qr_code_image`: QR code image path
- `qr_code_link`: QR code external URL

#### Relationships
```php
// Get restaurant
$place->restaurant;

// Get tables (when implemented)
// $place->tables;
```

#### Query Scopes
```php
// Active places
Place::active()->get();

// Places by restaurant
Place::byRestaurant($restaurantId)->get();

// Order by rank
Place::orderByRank('asc')->get();
```

#### Accessor Methods
```php
// Check if place is active
$isActive = $place->is_active;

// Get full image URL
$imageUrl = $place->full_image_url;

// Get full QR code URL
$qrCodeUrl = $place->full_qr_code_url;
```

#### Utility Methods
```php
// Generate slug from name
$slug = $place->generateSlug();
```

## Integration with Restaurant System

### Navigation Flow
1. **Restaurant Details** → `/api/website/restaurants/{slug}`
2. **Restaurant Places** → `/api/website/places/restaurant/slug/{slug}`
3. **Place Details** → `/api/website/places/slug/{slug}`
4. **Place Tables** → *Future implementation*

### Use Cases
- **Restaurant Layout**: Different dining areas (indoor, outdoor, private rooms)
- **Capacity Management**: Organize tables by sections
- **Customer Preferences**: VIP areas, quiet zones, family sections
- **Event Management**: Separate areas for different events

## Multi-language Support
All translatable fields automatically use the current locale:
- Georgian (`ka`)
- English (`en`) 
- Russian (`ru`)
- Turkish (`tr`)

## Usage Examples

### Frontend Integration
```javascript
// Get places for a restaurant
const response = await fetch('/api/website/places/restaurant/slug/restaurant-name?sort_by=rank_asc');
const places = await response.json();

// Get place details
const placeResponse = await fetch('/api/website/places/slug/main-dining-hall');
const place = await placeResponse.json();

// Search places
const searchResponse = await fetch('/api/website/places/search?search=terrace&restaurant_id=1');
const results = await searchResponse.json();
```

### Mobile App Optimization
- **PlaceShortResource**: Optimized for listing views
- **PlaceResource**: Full details for place pages
- **Image Handling**: Automatic URL generation for images
- **QR Code Support**: Full QR code URL generation

### Backend Usage
```php
// Get active places for a restaurant
$places = Place::byRestaurant($restaurantId)
    ->active()
    ->orderByRank()
    ->with(['restaurant', 'translations'])
    ->paginate(15);

// Find place by slug
$place = Place::where('slug', $slug)
    ->active()
    ->with(['restaurant', 'translations'])
    ->firstOrFail();

// Get featured places
$featured = Place::active()
    ->orderByRank()
    ->limit(10)
    ->get();
```

## Error Handling

### Common Scenarios
- **404 Not Found**: Place slug doesn't exist or place is inactive
- **Validation Errors**: Invalid search parameters
- **Empty Results**: No places match search criteria

### Response Format
```json
{
    "message": "Place not found",
    "status": 404
}
```

## Performance Considerations

### Database Optimization
- **Eager Loading**: Translations and restaurant data pre-loaded
- **Indexes**: Optimized queries on restaurant_id, slug, status
- **Pagination**: Large result sets are paginated

### Caching Strategy
- **Static Data**: Places can be cached with restaurant invalidation
- **Search Results**: Cache popular search terms
- **Images**: CDN integration for image delivery

## Future Enhancements

### Table Management Integration
When Table module is implemented:
```php
// Get tables for a place
$tables = $place->tables()->active()->get();

// Get available tables
$availableTables = $place->tables()
    ->available()
    ->forDateTime($dateTime)
    ->get();
```

### Capacity Management
```php
// Get place capacity
$capacity = $place->total_capacity;

// Check availability
$isAvailable = $place->hasAvailability($dateTime, $partySize);
```

### Advanced Features
- **Real-time Availability**: Integration with booking system
- **Floor Plans**: Visual representation of places
- **Ambient Data**: Noise level, lighting, atmosphere
- **Photo Galleries**: Multiple images per place

## Testing

### Test Coverage
- Model relationships and scopes
- API endpoint responses
- Search functionality
- Multi-language support
- Resource transformation

### Example Test Cases
```php
// Test place listing
$this->get('/api/website/places?restaurant_id=1')
    ->assertOk()
    ->assertJsonStructure(['data', 'meta']);

// Test place details
$this->get("/api/website/places/slug/{$place->slug}")
    ->assertOk()
    ->assertJson(['data' => ['id' => $place->id]]);

// Test search functionality
$this->get('/api/website/places/search?search=terrace')
    ->assertOk()
    ->assertJsonStructure(['data', 'meta']);
```

## Related Documentation
- [Restaurant Module](../restaurants/RESTAURANT-MODULE-INSTRUCTIONS.md)
- [Table Module](../tables/TABLE-MODULE-INSTRUCTIONS.md) *(Future)*
- [API Response Formats](../../api/API-RESPONSE-FORMATS.md)
- [Multi-language Support](../../i18n/TRANSLATION-GUIDE.md)
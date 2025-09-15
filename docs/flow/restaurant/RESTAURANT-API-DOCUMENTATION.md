# Restaurant API Documentation

## Overview
The Restaurant API provides comprehensive endpoints for accessing restaurant data, including basic information, places, tables, and menu structures. All endpoints support multi-language responses via the `locale` query parameter.

## Base URL
```
/api/website/restaurants
```

## Authentication
All endpoints are public and do not require authentication.

## Common Query Parameters
- `locale` (optional): Language code (ka, en, ru, tr) for localized content
- `per_page` (optional): Number of items per page for paginated endpoints (default: 15)

## Endpoints

### 1. List All Restaurants
```http
GET /api/website/restaurants
```

**Description:** Retrieve a paginated list of all active restaurants with filtering and sorting options.

**Query Parameters:**
- `search` (string): Search in restaurant name and description
- `category` (string): Filter by category slug
- `city` (string): Filter by city name
- `sort` (string): Sorting format: `{field}_{direction}` (e.g., `rank_asc`, `discount_rate_desc`)
- `per_page` (integer): Items per page (default: 15, max: 50)
- `locale` (string): Language code for translations

**Available Sort Fields:**
- `rank` (default)
- `discount_rate`
- `created_at`
- `updated_at`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name",
      "description": "Restaurant Description",
      "status": 1,
      "is_active": true,
      "image": "restaurants/image.jpg",
      "full_image_url": "https://domain.com/storage/restaurants/image.jpg",
      "discount_rate": 15,
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 67
  }
}
```

### 2. Get Restaurant by Slug
```http
GET /api/website/restaurants/{slug}
```

**Description:** Retrieve detailed information about a specific restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier

**Response:**
```json
{
  "data": {
    "id": 1,
    "slug": "restaurant-slug",
    "name": "Restaurant Name",
    "description": "Detailed restaurant description",
    "status": 1,
    "is_active": true,
    "image": "restaurants/image.jpg",
    "full_image_url": "https://domain.com/storage/restaurants/image.jpg",
    "discount_rate": 15,
    "address": "Restaurant Address",
    "phone": "+995 XXX XXX XXX",
    "email": "info@restaurant.com",
    "website": "https://restaurant.com",
    "working_hours": {
      "monday": "09:00-23:00",
      "tuesday": "09:00-23:00"
    },
    "translations": [
      {
        "locale": "ka",
        "name": "რესტორნის სახელი",
        "description": "რესტორნის აღწერა"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### 3. Get Restaurant Details
```http
GET /api/website/restaurants/{slug}/details
```

**Description:** Get comprehensive restaurant details with extended information.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier

**Response:** Same as Get Restaurant by Slug but with additional metadata and statistics.

---

## Places Endpoints

### 4. Get Restaurant Places
```http
GET /api/website/restaurants/{slug}/places
```

**Description:** Retrieve all places/areas within a specific restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier

**Query Parameters:**
- `locale` (string): Language code for translations

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "places": [
      {
        "id": 1,
        "slug": "main-hall",
        "name": "Main Hall",
        "description": "Main dining area",
        "status": 1,
        "is_active": true,
        "rank": 1,
        "image": "places/main-hall.jpg",
        "full_image_url": "https://domain.com/storage/places/main-hall.jpg",
        "qr_code": "QR123",
        "full_qr_code_url": "https://domain.com/storage/qr/place-qr.png"
      }
    ]
  }
}
```

### 5. Get Specific Place
```http
GET /api/website/restaurants/{slug}/place/{place}
```

**Description:** Get detailed information about a specific place within a restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier
- `place` (string): Place slug or ID

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "place": {
      "id": 1,
      "slug": "main-hall",
      "name": "Main Hall",
      "description": "Main dining area with beautiful view",
      "status": 1,
      "is_active": true,
      "rank": 1,
      "image": "places/main-hall.jpg",
      "full_image_url": "https://domain.com/storage/places/main-hall.jpg",
      "qr_code": "QR123",
      "qr_code_image": "qr/place-qr.png",
      "full_qr_code_url": "https://domain.com/storage/qr/place-qr.png",
      "restaurant_id": 1,
      "translations": [
        {
          "locale": "ka",
          "name": "მთავარი დარბაზი",
          "description": "მთავარი სასადილო ადგილი"
        }
      ]
    }
  }
}
```

---

## Tables Endpoints

### 6. Get All Restaurant Tables
```http
GET /api/website/restaurants/{slug}/tables
```

**Description:** Retrieve all available tables in a restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier

**Query Parameters:**
- `locale` (string): Language code for translations

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "tables": [
      {
        "id": 1,
        "slug": "table-01",
        "name": "Table 01",
        "description": "Window table for 4 people",
        "status": 1,
        "status_label": "Active",
        "is_active": true,
        "is_available": true,
        "is_reserved": false,
        "rank": 1,
        "seats": 4,
        "capacity": 4,
        "icon": "table-icon.svg",
        "image": "tables/table-01.jpg",
        "full_image_url": "https://domain.com/storage/tables/table-01.jpg",
        "restaurant_id": 1,
        "place_id": 1
      }
    ]
  }
}
```

### 7. Get Specific Table
```http
GET /api/website/restaurants/{slug}/table/{table}
```

**Description:** Get detailed information about a specific table.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier
- `table` (string): Table slug or ID

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "table": {
      "id": 1,
      "slug": "table-01",
      "name": "Table 01",
      "description": "Premium window table with city view",
      "status": 1,
      "status_label": "Active",
      "is_active": true,
      "is_available": true,
      "is_reserved": false,
      "rank": 1,
      "seats": 4,
      "capacity": 4,
      "icon": "table-icon.svg",
      "image": "tables/table-01.jpg",
      "full_image_url": "https://domain.com/storage/tables/table-01.jpg",
      "latitude": 41.7151,
      "longitude": 44.8271,
      "qr_code_image": "qr/table-qr.png",
      "qr_code_link": "https://restaurant.com/table/1",
      "qr_code_url": "https://restaurant.com/table/1",
      "full_qr_code_url": "https://domain.com/storage/qr/table-qr.png",
      "restaurant_id": 1,
      "place_id": 1,
      "restaurant": {
        "id": 1,
        "slug": "restaurant-slug",
        "translations": [
          {
            "locale": "ka",
            "name": "რესტორნის სახელი"
          }
        ]
      },
      "place": {
        "id": 1,
        "slug": "main-hall",
        "translations": [
          {
            "locale": "ka",
            "name": "მთავარი დარბაზი"
          }
        ]
      },
      "translations": [
        {
          "locale": "ka",
          "name": "მაგიდა 01",
          "description": "პრემიუმ მაგიდა ფანჯრიდან ხედით"
        }
      ]
    }
  }
}
```

### 8. Get Tables in Specific Place
```http
GET /api/website/restaurants/{slug}/place/{place}/tables
```

**Description:** Retrieve all tables within a specific place/area of a restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier
- `place` (string): Place slug or ID

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "place": {
      "id": 1,
      "slug": "main-hall",
      "name": "Main Hall"
    },
    "tables": [
      {
        "id": 1,
        "slug": "table-01",
        "name": "Table 01",
        "capacity": 4,
        "status_label": "Active",
        "is_available": true
      }
    ]
  }
}
```

### 9. Get Specific Table in Place
```http
GET /api/website/restaurants/{slug}/place/{place}/table/{table}
```

**Description:** Get detailed information about a specific table within a specific place.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier
- `place` (string): Place slug or ID
- `table` (string): Table slug or ID

**Alternative Shorter URL:**
```http
GET /api/website/restaurants/{slug}/{place}/{table}
```

**Response:** Combined restaurant, place, and table details with full relationships.

---

## Menu Endpoints

### 10. Get Restaurant Menu
```http
GET /api/website/restaurants/{slug}/menu
```

**Description:** Get the complete menu structure for a restaurant.

**Path Parameters:**
- `slug` (string): Restaurant slug identifier

**Response:**
```json
{
  "data": {
    "restaurant": {
      "id": 1,
      "slug": "restaurant-slug",
      "name": "Restaurant Name"
    },
    "menu": []
  }
}
```

### 11. Get Menu Categories
```http
GET /api/website/restaurants/{slug}/menu/categories
```

**Description:** Get all menu categories for a restaurant.

### 12. Get Specific Menu Category
```http
GET /api/website/restaurants/{slug}/menu/category/{categorySlug}
```

**Description:** Get details of a specific menu category.

### 13. Get Menu Category Items
```http
GET /api/website/restaurants/{slug}/menu/category/{categorySlug}/items
```

**Description:** Get all menu items within a specific category.

### 14. Get Specific Menu Item
```http
GET /api/website/restaurants/{slug}/menu/item/{itemSlug}
```

**Description:** Get details of a specific menu item.

### 15. Get All Menu Items
```http
GET /api/website/restaurants/{slug}/menu/items
```

**Description:** Get all menu items for a restaurant.

---

## Status Codes

- **200 OK**: Request successful
- **404 Not Found**: Restaurant, place, or table not found
- **422 Unprocessable Entity**: Invalid parameters
- **500 Internal Server Error**: Server error

## Error Response Format
```json
{
  "error": "Resource not found",
  "message": "Additional error details"
}
```

## Usage Examples

### Get Restaurant with Georgian Locale
```bash
curl "https://api.foodlyapp.ge/api/website/restaurants/restaurant-slug?locale=ka"
```

### Search Restaurants by Category
```bash
curl "https://api.foodlyapp.ge/api/website/restaurants?category=georgian-cuisine&per_page=10"
```

### Get Available Tables
```bash
curl "https://api.foodlyapp.ge/api/website/restaurants/restaurant-slug/tables?locale=en"
```

### Get Specific Table Details
```bash
curl "https://api.foodlyapp.ge/api/website/restaurants/restaurant-slug/table/table-01?locale=ka"
```

## Notes

1. All endpoints support the `locale` parameter for multi-language responses
2. Places and tables are hierarchically organized: Restaurant → Place → Table
3. Tables can be accessed both directly and through their parent place
4. All image URLs are automatically generated with full domain paths
5. QR codes are supported for both places and tables
6. Table statuses: Active (1), Inactive (0), Reserved (2), Maintenance (3)
7. Only active restaurants, places, and available tables are returned by default
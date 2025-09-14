# Spots API Documentation

## 🏢 Spots Endpoints

### Updated: September 15, 2025

Spots API provides access to restaurant/venue location data across all platforms.

---

## 🔐 Protected Endpoints (Authentication Required)

### Authentication Header
All protected endpoints require the `Authorization` header:
```
Authorization: Bearer {token}
```

---

## 📍 Platform-Specific Spots Endpoints

### 1. Kiosk Platform
**GET** `/api/kiosk/spots`

**Description:** Get active spots for kiosk platform with pagination

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `locale` (optional) - Response language (ka, en, ru, tr)
- `page` (optional) - Page number for pagination

**Response (200):**
```json
{
  "data": [
    {
      "id": 4,
      "status": "active",
      "rank": 1,
      "slug": "restaurant",
      "name": "Restaurant",
      "image": null,
      "image_link": null
    },
    {
      "id": 3,
      "status": "active", 
      "rank": 2,
      "slug": "bar",
      "name": "Bar",
      "image": null,
      "image_link": null
    }
  ],
  "links": {
    "first": "http://api.foodlyapp.ge.test/api/kiosk/spots?page=1",
    "last": "http://api.foodlyapp.ge.test/api/kiosk/spots?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "per_page": 12,
    "total": 4
  }
}
```

### 2. Android Platform
**GET** `/api/android/spots`

Same structure as kiosk platform with Android-specific optimizations.

### 3. iOS Platform  
**GET** `/api/ios/spots`

Same structure as kiosk platform with iOS-specific optimizations.

### 4. Website Platform
**GET** `/api/website/spots`

Same structure as kiosk platform with web-specific optimizations.

---

## 🧪 Public Test Endpoints (No Authentication)

### Test Endpoints for Development
```
GET /api/kiosk/spots/test     - Test kiosk spots endpoint
GET /api/android/spots/test   - Test android spots endpoint  
GET /api/ios/spots/test       - Test iOS spots endpoint
GET /api/website/spots/test   - Test website spots endpoint
```

**Response (200):**
```json
{
  "message": "Kiosk spots endpoint is working!",
  "platform": "kiosk",
  "timestamp": "2025-09-15T02:00:00Z"
}
```

---

## 📊 Spot Data Structure

### Spot Object
```json
{
  "id": 4,
  "status": "active|inactive|maintenance", 
  "rank": 1,
  "slug": "restaurant",
  "name": "Restaurant",
  "image": "path/to/image.jpg",
  "image_link": "https://example.com/image.jpg"
}
```

### Status Types
- `active` - Spot is available and operational
- `inactive` - Spot is temporarily unavailable
- `maintenance` - Spot is under maintenance

---

## 🔍 Filtering and Pagination

### Default Behavior
- Only `active` spots are returned
- Results are ordered by `rank` (ascending)
- 12 items per page
- Supports Laravel pagination

### Error Responses

**404 - No Spots Found:**
```json
{
  "error": "No spots found"
}
```

**500 - Server Error:**
```json
{
  "error": "Failed to fetch spots",
  "message": "Database connection error details"
}
```

---

## 🌐 Internationalization

### Locale Support
Add `?locale=ka` to get localized spot names:
```
GET /api/kiosk/spots?locale=ka
```

**Supported Locales:**
- `ka` - Georgian (ქართული)
- `en` - English  
- `ru` - Russian (Русский)
- `tr` - Turkish (Türkçe)

**Fallback:** English if locale not supported or translation missing.
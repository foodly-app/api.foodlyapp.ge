# FOODLY API - Test Endpoints Documentation

## 📋 Overview

FOODLY API test endpoints უზრუნველყოფს API functionality verification-ს ყველა platform-ისთვის authentication-ის გარეშე.

## 🔓 Public Test Endpoints

### Platform-Specific Test Endpoints

#### 🖥 Kiosk Platform Test
```http
GET /api/kiosk/test
```

**Query Parameters:**
- `locale` (optional) - ka, en, ru, tr

**Example Request:**
```bash
curl "http://api.foodlyapp.test/api/kiosk/test?locale=ka"
```

**Response:**
```json
{
  "status": "success",
  "platform": "kiosk",
  "locale": "ka",
  "message": "Kiosk test endpoint მუშაობს - ქართული ლოკალით",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "endpoint": "GET /api/kiosk/test"
}
```

#### 📱 Android Platform Test
```http
GET /api/android/test
```

**Example Request:**
```bash
curl "http://api.foodlyapp.test/api/android/test?locale=en"
```

**Response:**
```json
{
  "status": "success",
  "platform": "android",
  "locale": "en",
  "message": "Android test endpoint working - English locale",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "endpoint": "GET /api/android/test"
}
```

#### 🍎 iOS Platform Test
```http
GET /api/ios/test
```

**Example Request:**
```bash
curl "http://api.foodlyapp.test/api/ios/test?locale=ru"
```

**Response:**
```json
{
  "status": "success",
  "platform": "ios",
  "locale": "ru",
  "message": "iOS test endpoint working - Russian locale",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "endpoint": "GET /api/ios/test"
}
```

#### 🌐 Website Platform Test
```http
GET /api/website/test
```

**Example Request:**
```bash
curl "http://api.foodlyapp.test/api/website/test?locale=en"
```

**Response:**
```json
{
  "status": "success",
  "platform": "website",
  "locale": "en",
  "message": "Website test endpoint working - English locale",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "endpoint": "GET /api/website/test"
}
```

## 🔒 Protected Endpoints (Authentication Required)

### Restaurant Endpoints
```http
GET /api/kiosk/restaurants     # Requires Sanctum token
GET /api/android/restaurants   # Requires Sanctum token  
GET /api/ios/restaurants       # Requires Sanctum token
GET /api/website/restaurants       # Requires Sanctum token
```

**Authentication Example:**
```bash
# 1. Login to get token
curl -X POST "http://api.foodlyapp.test/api/auth/login" \
     -H "Content-Type: application/json" \
     -d '{"email":"test@foodlyapp.ge","password":"password123"}'

# 2. Use token in restaurant requests
curl -H "Authorization: Bearer 1|xxxxx" \
     "http://api.foodlyapp.test/api/kiosk/restaurants?locale=ka"
```

## 🛠 Database Test Endpoints

### Database Connection Test
```http
GET /api/test/db-connection
```

**Purpose:** Verify database connectivity and show connection details.

**Response:**
```json
{
  "status": "success",
  "database": {
    "connection": "mysql",
    "database": "api_db",
    "status": "connected"
  },
  "timestamp": "2025-09-15T10:30:00.000000Z"
}
```

### Table Structure Test
```http
GET /api/test/table/{table_name}
```

**Example:**
```bash
curl "http://api.foodlyapp.test/api/test/table/users"
```

## 🌐 Locale Testing

### Locale Detection Methods

#### 1. Query Parameter (Highest Priority)
```bash
curl "http://api.foodlyapp.test/api/kiosk/test?locale=ka"
```

#### 2. Accept-Language Header
```bash
curl -H "Accept-Language: ka-GE,ka;q=0.9,en;q=0.8" \
     "http://api.foodlyapp.test/api/kiosk/test"
```

#### 3. Fallback Behavior
```bash
# Unsupported locale defaults to 'en'
curl "http://api.foodlyapp.test/api/kiosk/test?locale=fr"
```

### Supported Locales
- `ka` - Georgian (ქართული)
- `en` - English (default fallback)
- `ru` - Russian (Русский)  
- `tr` - Turkish (Türkçe)

## 📊 Test Endpoint Comparison

| Platform | URL | Default Locale | Auth Required |
|----------|-----|----------------|---------------|
| Kiosk    | `/api/kiosk/test` | `ka` | ❌ No |
| Android  | `/api/android/test` | `en` | ❌ No |
| iOS      | `/api/ios/test` | `ru` | ❌ No |
| Website  | `/api/website/test` | `en` | ❌ No |

## ✅ Expected Behavior

### All Platforms Should Return:
- ✅ HTTP 200 status code
- ✅ JSON response format
- ✅ Platform identification
- ✅ Current locale
- ✅ Localized message
- ✅ Timestamp in ISO format
- ✅ Endpoint path information
- ✅ `X-App-Locale` header

### Common Response Headers:
```http
Content-Type: application/json
X-App-Locale: ka
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Endpoint Not Found (404)
```bash
# Check if routes are registered
php artisan route:list --path=api
```

#### 2. Wrong Locale Response
```bash
# Verify locale parameter syntax
?locale=ka  # ✅ Correct
?local=ka   # ❌ Wrong parameter name
```

#### 3. Server Error (500)
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log
```

### Debug Commands
```bash
# View all API routes
php artisan route:list --path=api

# Clear route cache
php artisan route:clear

# Clear config cache  
php artisan config:clear
```

## 🎯 Testing Scenarios

### Quick Health Check
```bash
# Test all platforms
curl "http://api.foodlyapp.test/api/kiosk/test"
curl "http://api.foodlyapp.test/api/android/test"  
curl "http://api.foodlyapp.test/api/ios/test"
curl "http://api.foodlyapp.test/api/website/test"
```

### Locale Verification  
```bash
# Test locale detection
curl "http://api.foodlyapp.test/api/kiosk/test?locale=ka"
curl "http://api.foodlyapp.test/api/android/test?locale=en"
curl "http://api.foodlyapp.test/api/ios/test?locale=ru"
curl "http://api.foodlyapp.test/api/website/test?locale=en"
```

### Error Testing
```bash
# Test fallback behavior
curl "http://api.foodlyapp.test/api/kiosk/test?locale=invalid"
```

Test endpoints მზადაა development და QA testing-ისთვის! 🧪
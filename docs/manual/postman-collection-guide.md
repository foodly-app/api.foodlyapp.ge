# FOODLY API - Current Documentation

## 📋 API Overview

FOODLY API არის multi-platform food delivery API with Laravel Sanctum authentication და locale support. 

## 🏗 Current API Structure

### 🔐 Authentication Endpoints
```
POST /api/auth/register     - User registration
POST /api/auth/login        - User login (returns token)
POST /api/auth/logout       - User logout (requires auth)
GET  /api/user/             - Get user profile (requires auth)
POST /api/user/refresh-token - Refresh auth token (requires auth)
```

### � Platform-Specific Public Test Endpoints
```
GET /api/kiosk/test    - Kiosk platform test (Georgian locale focus)
GET /api/android/test  - Android platform test (English locale focus)  
GET /api/ios/test      - iOS platform test (Russian locale focus)
```

### 🔒 Platform-Specific Protected Endpoints (Require Auth)
```
GET /api/kiosk/restaurants    - Kiosk restaurants (requires Sanctum token)
GET /api/android/restaurants  - Android restaurants (requires Sanctum token)
GET /api/ios/restaurants      - iOS restaurants (requires Sanctum token)
```

### 🛠 Database Testing Endpoints
```
GET /api/test/db-connection   - Database connectivity test
GET /api/test/table/{table}   - Table structure inspection
```

## 🌐 Locale Support

### Supported Locales
- `ka` - Georgian (ქართული)
- `en` - English  
- `ru` - Russian (Русский)
- `tr` - Turkish (Türkçe)

### Locale Detection Methods
1. **Query Parameter**: `?locale=ka`
2. **Accept-Language Header**: `Accept-Language: ka-GE,ka;q=0.9,en;q=0.8`
3. **Fallback**: Unsupported locales default to `en`

## 🔑 Authentication Flow

### Laravel Sanctum Token-Based Auth
```bash
# 1. Register
POST /api/auth/register
{
  "name": "Test User",
  "email": "test@foodlyapp.ge", 
  "password": "password123",
  "password_confirmation": "password123"
}

# 2. Login (get token)
POST /api/auth/login
{
  "email": "test@foodlyapp.ge",
  "password": "password123"
}
# Response: {"data": {"token": "1|xxxxx"}}

# 3. Use token in headers
Authorization: Bearer 1|xxxxx
```

## � Platform Architecture

### Platform Separation
- **Kiosk**: Georgian-focused, restaurant kiosk systems
- **Android**: English-focused, mobile Android app
- **iOS**: Russian-focused, mobile iOS app

### Controllers Structure
```
app/Http/Controllers/Api/
├── AuthController.php              (Authentication)
├── Kiosk/RestaurantController.php  (Kiosk platform)
├── Android/RestaurantController.php (Android platform)
└── Ios/RestaurantController.php    (iOS platform)
```

### Routes Structure
```
routes/
├── api.php           (Main auth routes)
├── Api/kiosk.php     (Kiosk platform routes)
├── Api/android.php   (Android platform routes)
└── Api/ios.php       (iOS platform routes)
```

## 🔧 Middleware Configuration

### SetLocale Middleware
- Detects locale from query params or headers
- Sets application locale for responses
- Applied to all platform endpoints

### Authentication Middleware
- `auth:sanctum` - Protects restaurant endpoints
- Public test endpoints bypass authentication

## 📊 Response Format

### Standard API Response
```json
{
  "status": "success",
  "platform": "kiosk|android|ios", 
  "locale": "ka|en|ru|tr",
  "message": "Localized message",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "data": {},
  "endpoint": "GET /api/platform/endpoint"
}
```

### Authentication Response
```json
{
  "status": "success",
  "message": "User logged in successfully",
  "data": {
    "token": "1|xxxxxxxxxxxxx",
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@foodlyapp.ge"
    }
  }
}
```

## 🧪 Testing

### Manual Testing URLs
```bash
# Public test endpoints (no auth)
curl "http://api.foodlyapp.test/api/kiosk/test?locale=ka"
curl "http://api.foodlyapp.test/api/android/test?locale=en" 
curl "http://api.foodlyapp.test/api/ios/test?locale=ru"

# Protected endpoints (requires auth token)
curl -H "Authorization: Bearer TOKEN" \
     "http://api.foodlyapp.test/api/kiosk/restaurants?locale=ka"
```

### Development Environment
- **Laravel Herd**: Local development server
- **Domain**: `api.foodlyapp.test`
- **Database**: MySQL (api_db)
- **PHP**: 8.x with Laravel 12.x

## 📁 Postman Collection (Optional)

### Files Available
- `FOODLY-API-Collection.postman_collection.json` - Complete collection
- `FOODLY-API-Environment.postman_environment.json` - Environment setup

### Import Instructions
1. Open Postman
2. File → Import both JSON files  
3. Select "FOODLY API - Local Environment"
4. Run "Login User" to auto-save auth token
5. Test any endpoint!

## 🔍 Troubleshooting

### Common Issues
1. **401 Unauthorized**: Login first to get auth token
2. **Locale not working**: Check query param `?locale=ka` or Accept-Language header
3. **Routes not found**: Clear route cache: `php artisan route:clear`

### Debug Commands
```bash
php artisan route:list           # View all routes
php artisan route:list --path=api # View API routes only
php artisan config:clear         # Clear config cache
```

## 🎯 Current Status

### ✅ Completed Features
- Authentication system with Sanctum tokens
- Multi-platform architecture (Kiosk/Android/iOS)
- Locale detection and translation support  
- Test endpoints for all platforms
- Database connectivity testing
- Comprehensive documentation

### 🚀 Ready for Development
API infrastructure მზადაა production development-ისთვის:
- Add actual restaurant data models
- Implement menu management  
- Add order processing
- Integrate payment systems
- Add real-time features
- **iOS Restaurants** - Authentication required

### 🛠 Database Testing
- **Database Connection Test** - DB connectivity check
- **Database Table Structure** - Table structure inspection

### 🌐 Locale Testing
- **Accept-Language Header Test** - Header-based locale detection
- **All Supported Locales** - Testing en, ka, ru, tr locales

## 🤖 Automated Testing

### Pre-Request Scripts
```javascript
// Login endpoint automatically saves token
if (pm.response.code === 200) {
    const response = pm.response.json();
    if (response.data && response.data.token) {
        pm.environment.set('auth_token', response.data.token);
    }
}
```

### Test Scripts Examples
```javascript
// Platform verification
pm.test('Platform is kiosk', function () {
    const response = pm.response.json();
    pm.expect(response.platform).to.eql('kiosk');
});

// Locale verification
pm.test('Locale is ka', function () {
    const response = pm.response.json();
    pm.expect(response.locale).to.eql('ka');
});

// Response time check
pm.test('Response time is less than 2000ms', function () {
    pm.expect(pm.response.responseTime).to.be.below(2000);
});
```

## 🔄 Testing Workflow

### 1. Initial Setup
1. Run "Register User" (თუ არ არსებობს)
2. Run "Login User" → token იავტომატურად შეინახება

### 2. Public Endpoints Testing
- Test ყველა platform endpoint authentication-ის გარეშე
- Verify locale detection და fallback

### 3. Protected Endpoints Testing
- Test authentication required endpoints
- Verify platform-specific responses

### 4. Locale Testing
- Test query parameter locale
- Test Accept-Language header
- Test unsupported locale fallback

## 📊 Expected Responses

### Test Endpoint Response
```json
{
  "status": "success",
  "platform": "kiosk",
  "locale": "ka",
  "message": "Kiosk test endpoint მუშაობს - ქართული ლოკალით",
  "timestamp": "2025-09-15T10:30:00.000000Z",
  "endpoint": "/api/kiosk/test"
}
```

### Authentication Response
```json
{
  "status": "success",
  "message": "User logged in successfully",
  "data": {
    "token": "1|xxxxxxxxxxxxxxxxxxx",
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@foodlyapp.ge"
    }
  }
}
```

### Restaurant Endpoint Response
```json
{
  "status": "success",
  "platform": "android",
  "locale": "en",
  "message": "Android restaurants endpoint working - English locale",
  "data": {
    "restaurants": [],
    "authenticated_user": {
      "id": 1,
      "name": "Test User"
    }
  }
}
```

## 🔧 Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Run "Login User" request first
   - Check if auth_token is saved in environment

2. **Locale not working**
   - Check query parameter: `?locale=ka`
   - Verify Accept-Language header format

3. **Base URL issues**
   - Verify Laravel Herd is running
   - Check `api.foodlyapp.test` domain access

### Debug Tips
- Console.log-ები ყველა request-ში
- Response time monitoring
- Automatic content-type verification

## 📈 Collection Features

### ✅ Global Test Scripts
- Response time validation (<2000ms)
- Content-Type verification
- Request logging

### ✅ Environment Variables
- Base URL configuration
- Automatic token management
- Test user credentials

### ✅ Platform Testing
- Kiosk, Android, iOS separation
- Locale-specific responses
- Authentication vs public endpoints

### ✅ Error Handling
- Unauthenticated responses
- Locale fallback testing
- Database connection validation

## 🎯 Usage Tips

1. **Sequential Testing**: Authentication → Public → Protected endpoints
2. **Batch Testing**: Run entire folders with Collection Runner
3. **Environment Switching**: Easy switch between local/staging/production
4. **Automated Workflows**: CI/CD integration ready with Newman

Collection მზადაა production testing-ისთვის და development workflow-ისთვის!
# FOODLY API - Complete Documentation

## 📚 Documentation Index

### 📖 Core Documentation
- [API Architecture Overview](./manual/api-architecture.md) - System architecture და technical overview
- [Current API Status](./manual/postman-collection-guide.md) - ამჟამინდელი API endpoints და functionality  
- [Test Endpoints Guide](./manual/test-endpoints.md) - Testing endpoints და debugging

## 🎯 Current Project Status

### ✅ Completed Features

#### � Authentication System
- Laravel Sanctum token-based authentication
- User registration და login endpoints
- Token refresh functionality
- Protected route middleware

#### 📱 Multi-Platform Architecture
- **Kiosk Platform** - Georgian locale focus (Restaurant kiosks)
- **Android Platform** - English locale focus (Mobile app)
- **iOS Platform** - Russian locale focus (Mobile app)

#### 🌐 Internationalization
- Locale detection from query parameters
- Accept-Language header support
- Fallback to English for unsupported locales
- Supported: Georgian (ka), English (en), Russian (ru), Turkish (tr)

#### 🧪 Testing Infrastructure
- Public test endpoints for all platforms
- Database connectivity testing
- Comprehensive Postman collection
- Automated test scripts

### 🛣 API Endpoints Summary

#### Authentication Endpoints
```
POST /api/auth/register     - User registration
POST /api/auth/login        - User login (get token)
POST /api/auth/logout       - User logout (requires auth)
GET  /api/user/             - User profile (requires auth)
POST /api/user/refresh-token - Token refresh (requires auth)
```

#### Platform Test Endpoints (Public)
```
GET /api/kiosk/test    - Kiosk platform test
GET /api/android/test  - Android platform test
GET /api/ios/test      - iOS platform test
```

#### Platform Restaurant Endpoints (Protected)
```
GET /api/kiosk/restaurants    - Kiosk restaurants (auth required)
GET /api/android/restaurants  - Android restaurants (auth required)
GET /api/ios/restaurants      - iOS restaurants (auth required)
```

#### Database Testing
```
GET /api/test/db-connection   - Database connectivity test
GET /api/test/table/{table}   - Table structure inspection
```

### 🏗 Technical Architecture

#### Framework & Dependencies
- **Laravel 12.x** - Core framework
- **Laravel Sanctum** - API authentication
- **astrotomic/laravel-translatable** - Localization support
- **MySQL** - Production database (api_db)
- **Laravel Herd** - Local development environment

#### Project Structure
```
app/Http/Controllers/Api/
├── AuthController.php              # Authentication logic
├── Kiosk/RestaurantController.php  # Kiosk platform
├── Android/RestaurantController.php # Android platform
└── Ios/RestaurantController.php    # iOS platform

routes/
├── api.php           # Main authentication routes
├── Api/kiosk.php     # Kiosk platform routes
├── Api/android.php   # Android platform routes
└── Api/ios.php       # iOS platform routes

app/Http/Middleware/
└── SetLocale.php     # Custom locale detection middleware
```

## 🚀 Quick Start

### Development Setup
```bash
# 1. Environment Setup
git clone <repository>
cd api.foodlyapp.ge
composer install

# 2. Database Configuration
# Setup MySQL database: api_db
# Configure .env file with database credentials

# 3. Laravel Setup
php artisan migrate
php artisan db:seed

# 4. Start Development (Laravel Herd)
# Access: http://api.foodlyapp.test
```

### Quick API Testing
```bash
# Test all platforms
curl "http://api.foodlyapp.test/api/kiosk/test?locale=ka"
curl "http://api.foodlyapp.test/api/android/test?locale=en" 
curl "http://api.foodlyapp.test/api/ios/test?locale=ru"

# Authentication flow
curl -X POST "http://api.foodlyapp.test/api/auth/login" \
     -H "Content-Type: application/json" \
     -d '{"email":"test@foodlyapp.ge","password":"password123"}'
```

### Postman Testing
1. Import `docs/FOODLY-API-Collection.postman_collection.json`
2. Import `docs/FOODLY-API-Environment.postman_environment.json`
3. Select "FOODLY API - Local Environment"
4. Run "Login User" to get authentication token
5. Test any endpoint!

## 🎯 Project Status

### ✅ Production Ready Infrastructure
- Multi-platform API architecture
- Authentication system with Sanctum
- Locale detection and internationalization
- Database connectivity and testing
- Comprehensive documentation
- Testing tools and endpoints

### 🚀 Ready for Business Logic Development
- Restaurant data models and CRUD
- Menu management system
- Order processing workflow
- Payment gateway integration
- Real-time notifications
- Analytics and reporting

API infrastructure მზადაა enterprise-level development-ისთვის! 🎯  
- 🇷🇺 Russian (ru)
- 🇹🇷 Turkish (tr)

### Tech Stack
- Laravel 12.x
- Livewire + Flux
- astrotomic/laravel-translatable
- SQLite (dev) / MySQL (prod)
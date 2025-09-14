# FOODLY API - Complete Documentation

## 📚 Documentation Index

### 📖 Core Documentation
- [API Architecture Overview](./manual/api-architecture.md) - System architecture და technical overview
- [API Authentication Guide](./manual/api-authentication.md) - Authentication system details
- [Spots API Documentation](./manual/spots-api.md) - Spots endpoints and data structure
- [Testing Guide](./manual/testing-guide.md) - Testing setup and procedures
- [Current API Status](./manual/postman-collection-guide.md) - ამჟამინდელი API endpoints და functionality  
- [Test Endpoints Guide](./manual/test-endpoints.md) - Testing endpoints და debugging

## 🎯 Current Project Status (Updated: September 15, 2025)

### ✅ Completed Features

#### 🔐 Authentication System
- Laravel Sanctum token-based authentication  
- Multi-platform client support (kiosk, android, ios, website)
- User login/register/logout endpoints
- Platform-specific token abilities
- Real user testing with production credentials

#### 📱 Multi-Platform API Architecture
- **Kiosk Platform** (`/api/kiosk/*`) - Restaurant kiosk integration
- **Android Platform** (`/api/android/*`) - Mobile app endpoints  
- **iOS Platform** (`/api/ios/*`) - iOS app endpoints
- **Website Platform** (`/api/website/*`) - Web app endpoints

#### 🗄️ Database Configuration
- **Production Database**: `api_db` (MySQL)
- **Test Database**: `api_db_test` (MySQL)
- **External Data**: `foodlyapp` database connection
- **Data Migration**: Spots data migrated from foodlyapp to api_db

#### 🏢 SpotController Implementation
- CRUD operations for all platforms
- Platform-specific responses
- Active status filtering
- Pagination support (12 items per page)
- Resource-based JSON responses

#### 🧪 Testing Infrastructure
- Pest testing framework integration  
- Database transactions for test isolation
- Real authentication flow testing
- Token generation validation
- Protected endpoint testing

### 🛣 API Endpoints Summary

#### Authentication Endpoints
```
POST /api/auth/login        - User login (get token)
POST /api/auth/register     - User registration  
POST /api/auth/logout       - User logout (requires auth)
```

#### Spots Endpoints (All Platforms)
```
GET /api/kiosk/spots        - Get spots for kiosk (requires auth)
GET /api/kiosk/spots/test   - Public test endpoint for kiosk

GET /api/android/spots      - Get spots for android (requires auth) 
GET /api/android/spots/test - Public test endpoint for android

GET /api/ios/spots          - Get spots for iOS (requires auth)
GET /api/ios/spots/test     - Public test endpoint for iOS

GET /api/website/spots      - Get spots for website (requires auth)
GET /api/website/spots/test - Public test endpoint for website
```

#### Platform Test Endpoints (Public)
```
GET /api/kiosk/test    - Kiosk platform test
GET /api/android/test  - Android platform test
GET /api/ios/test      - iOS platform test
GET /api/website/test  - Website platform test
```

#### Platform Restaurant Endpoints (Protected)
```
GET /api/kiosk/restaurants    - Kiosk restaurants (auth required)
GET /api/android/restaurants  - Android restaurants (auth required)
GET /api/ios/restaurants      - iOS restaurants (auth required)
GET /api/website/restaurants  - Website restaurants (auth required)
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
#### 🗄️ Database & Environment
- **MySQL** - Production database (api_db) 
- **Test Database** - api_db_test for testing isolation
- **External Data** - foodlyapp database connection
- **Laravel Herd** - Local development environment

#### 📁 Project Structure
```
app/Http/Controllers/Api/
├── AuthController.php                # Multi-platform authentication
├── Kiosk/SpotController.php         # Kiosk spots management
├── Android/SpotController.php       # Android spots management  
├── Ios/SpotController.php           # iOS spots management
└── Website/SpotController.php       # Website spots management

app/Http/Resources/Spot/
└── SpotResource.php                 # Spot API response formatting

app/Models/Spot/
└── Spot.php                        # Spot data model with translations

routes/
├── api.php                         # Main authentication routes
└── Api/
    ├── kiosk.php                   # Kiosk platform routes
    ├── android.php                 # Android platform routes
    ├── ios.php                     # iOS platform routes
    └── website.php                 # Website platform routes

tests/Feature/
├── Auth/ApiAuthenticationTest.php  # Authentication testing
└── TokenTest.php                   # Integration testing
```

---

## 🚀 Quick Start

### Development Setup
```bash
# 1. Environment Setup
git clone <repository>
cd api.foodlyapp.ge
composer install

# 2. Database Configuration
# Configure .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_db
DB_USERNAME=root
DB_PASSWORD=Admin1.

# 3. Laravel Setup
php artisan migrate
php artisan test  # Run test suite

# 4. Start Development (Laravel Herd)
# Access: http://api.foodlyapp.ge.test
```

### Quick API Testing

#### Authentication Flow
```bash
# Get authentication token
curl -X POST "http://api.foodlyapp.ge.test/api/auth/login" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "email": "gakhokia.david@gmail.com",
       "password": "Paroli_321!",
       "client": "kiosk"
     }'
```

#### Test Protected Endpoints
```bash
# Access spots with token
curl -X GET "http://api.foodlyapp.ge.test/api/kiosk/spots" \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
```

#### Public Test Endpoints
```bash
# Test all platforms (no auth required)
curl "http://api.foodlyapp.ge.test/api/kiosk/spots/test"
curl "http://api.foodlyapp.ge.test/api/android/spots/test" 
curl "http://api.foodlyapp.ge.test/api/ios/spots/test"
curl "http://api.foodlyapp.ge.test/api/website/spots/test"
```

### 🧪 Testing
```bash
# Run all tests
php artisan test

# Run authentication tests only  
php artisan test tests/Feature/Auth/

# Run token integration tests
php artisan test tests/Feature/TokenTest.php
```

### 📮 Postman Testing
1. Import `docs/FOODLY-API-Collection.postman_collection.json`
2. Import `docs/FOODLY-API-Environment.postman_environment.json`
3. Select "FOODLY API - Local Environment"
4. Run "Login User" to get authentication token
5. Test any endpoint!

---

## 🎯 Current Status & Next Steps

### ✅ Production Ready Features
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
# FOODLY API - Complete D### 📖 Core Documentation
- [API Architecture Overview](./manual/api-architecture.md) - System architecture და technical overview
- [API Authentication Guide](./manual/api-authentication.md) - Authentication system details
- [Spots API Documentation](./manual/spots-api.md) - Spots endpoints and data structure
- [Testing Guide](./manual/testing-guide.md) - Testing setup and procedures
- [Current API Status](./manual/postman-collection-guide.md) - ამჟამინდელი API endpoints და functionality  
- [Test Endpoints Guide](./manual/test-endpoints.md) - Testing endpoints და debugging

### 🆕 New Documentation (January 2025) ✨
- **🔥 [Postman Collection v2](./FOODLY-API-v2-Collection.postman_collection.json)** - Complete testing suite
- **🌍 [Production Environment](./FOODLY-API-Production.postman_environment.json)** - Ready-to-use environment
- **📮 [Postman Import Guide](./POSTMAN-IMPORT-GUIDE.md)** - Complete import & setup instructions
- **📝 [Release Notes](./RELEASE-NOTES.md)** - v2.0 changelog and achievements
- **⚡ [Quick Reference](./QUICK-REFERENCE.md)** - Copy-paste ready commands & URLsn

> **Production Ready** ✅ | Last Updated: January 2025  
> **Live API**: https://api.foodlyapp.ge

## � Quick Start

### Production API Test
```bash
curl -X GET "https://api.foodlyapp.ge/api/website/test" -H "Accept: application/json"
```

### Postman Collection (NEW v2 ✨)
**Download & Import:**
- [`FOODLY-API-v2-Collection.postman_collection.json`](./FOODLY-API-v2-Collection.postman_collection.json) 
- [`FOODLY-API-Production.postman_environment.json`](./FOODLY-API-Production.postman_environment.json)

### Test Credentials
- **Email**: `davit@foodlyapp.ge`
- **Password**: `11111111`

---

## �📚 Documentation Index

### 📖 Core Documentation
- [API Architecture Overview](./manual/api-architecture.md) - System architecture და technical overview
- [API Authentication Guide](./manual/api-authentication.md) - Authentication system details
- [Spots API Documentation](./manual/spots-api.md) - Spots endpoints and data structure
- [Testing Guide](./manual/testing-guide.md) - Testing setup and procedures
- [Current API Status](./manual/postman-collection-guide.md) - ამჟამინდელი API endpoints და functionality  
- [Test Endpoints Guide](./manual/test-endpoints.md) - Testing endpoints და debugging

### � New Documentation (January 2025)
- **🔥 [Updated Postman Collection v2](./FOODLY-API-v2-Collection.postman_collection.json)** - Complete testing suite
- **🌍 [Production Environment](./FOODLY-API-Production.postman_environment.json)** - Ready-to-use environment

---

## �🎯 Current Project Status (Updated: January 16, 2025)

### ✅ Completed Features

#### 🔐 Authentication System
- Laravel Sanctum token-based authentication  
- Multi-platform client support (kiosk, android, ios, website)
- User login/register/logout endpoints
- Platform-specific token abilities
- **✅ Production Ready**: Real user testing with production credentials

#### 📱 Multi-Platform API Architecture
- **Kiosk Platform** (`/api/kiosk/*`) - Restaurant kiosk integration
- **Android Platform** (`/api/android/*`) - Mobile app endpoints  
- **iOS Platform** (`/api/ios/*`) - iOS app endpoints
- **Website Platform** (`/api/website/*`) - Web app endpoints
- **✅ Route Conflicts Resolved**: Unique naming system implemented

#### 🗄️ Database Configuration
- **Production Database**: `api_db` (MySQL) ✅
- **Test Database**: `api_db_test` (MySQL) ✅  
- **External Data**: `foodlyapp` database connection ✅
- **Data Migration**: Spots data migrated from foodlyapp to api_db ✅

#### 🏢 SpotController Implementation ✨
- **✅ Complete CRUD operations** for all platforms
- **✅ Platform-specific responses** with unique route names
- **✅ Active status filtering** and sorting by rank
- **✅ Pagination support** (12 items per page)
- **✅ Resource-based JSON responses** with localization
- **✅ Production Deployment**: Live on Laravel Forge

#### 🧪 Testing Infrastructure
- Pest testing framework integration  
- Database transactions for test isolation
- Real authentication flow testing
- Token generation validation
- Protected endpoint testing
- **✅ Production Testing**: Verified working with live API

### 🛣 Production API Endpoints (Live ✅)

**Base URL**: `https://api.foodlyapp.ge`

#### Authentication Endpoints
```
POST /api/auth/login        - User login (get token) ✅
POST /api/auth/register     - User registration ✅ 
POST /api/auth/logout       - User logout (requires auth) ✅
```

#### Spots Endpoints (All Platforms) ✨
```
# Kiosk Platform
GET /api/kiosk/spots        - Get spots for kiosk (requires auth) ✅
GET /api/kiosk/spots/{id}   - Get single spot ✅
POST /api/kiosk/spots       - Create spot (requires auth) ✅
PUT /api/kiosk/spots/{id}   - Update spot (requires auth) ✅
DELETE /api/kiosk/spots/{id} - Delete spot (requires auth) ✅
GET /api/kiosk/spots/test   - Public test endpoint ✅

# Android Platform  
GET /api/android/spots      - Get spots for android (requires auth) ✅
GET /api/android/spots/{id} - Get single spot ✅
POST /api/android/spots     - Create spot (requires auth) ✅
PUT /api/android/spots/{id} - Update spot (requires auth) ✅
DELETE /api/android/spots/{id} - Delete spot (requires auth) ✅
GET /api/android/spots/test - Public test endpoint ✅

# iOS Platform
GET /api/ios/spots          - Get spots for iOS (requires auth) ✅
GET /api/ios/spots/{id}     - Get single spot ✅
POST /api/ios/spots         - Create spot (requires auth) ✅
PUT /api/ios/spots/{id}     - Update spot (requires auth) ✅
DELETE /api/ios/spots/{id}  - Delete spot (requires auth) ✅
GET /api/ios/spots/test     - Public test endpoint ✅

# Website Platform
GET /api/website/spots      - Get spots for website (requires auth) ✅
GET /api/website/spots/{id} - Get single spot ✅
POST /api/website/spots     - Create spot (requires auth) ✅
PUT /api/website/spots/{id} - Update spot (requires auth) ✅
DELETE /api/website/spots/{id} - Delete spot (requires auth) ✅
GET /api/website/test       - General test endpoint ✅
```

#### Restaurant Endpoints (All Platforms) ✅
```
GET /api/kiosk/restaurants        - Get restaurants for kiosk ✅
GET /api/android/restaurants      - Get restaurants for android ✅  
GET /api/ios/restaurants          - Get restaurants for iOS ✅
GET /api/website/restaurants      - Get restaurants for website ✅
```

#### Platform Test Endpoints (Public)
```
GET /api/kiosk/test    - Kiosk platform test
GET /api/android/test  - Android platform test
GET /api/ios/test      - iOS platform test
GET /api/website/test  - Website platform test
```

---

## 🌍 Production API Testing

### Quick Production Test
```bash
# Test API health (no auth required)
curl -X GET "https://api.foodlyapp.ge/api/website/test" \
     -H "Accept: application/json"

# Expected Response:
# {"message": "Website API is working", "timestamp": "2025-01-16T12:00:00Z"}
```

### Production Authentication Flow
```bash
# Get production authentication token
curl -X POST "https://api.foodlyapp.ge/api/auth/login" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "email": "davit@foodlyapp.ge",
       "password": "11111111",
       "client": "website",
       "device_name": "Production Test"
     }'
```

### Production Spots API Test
```bash
# Access production spots with token
curl -X GET "https://api.foodlyapp.ge/api/website/spots?locale=en" \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
```

---

## 📮 Postman Collection v2 (NEW ✨)

### Import Instructions
1. **Download Files**:
   - [`FOODLY-API-v2-Collection.postman_collection.json`](./FOODLY-API-v2-Collection.postman_collection.json)
   - [`FOODLY-API-Production.postman_environment.json`](./FOODLY-API-Production.postman_environment.json)

2. **Import to Postman**:
   - Collection → Import → Select collection file
   - Environment → Import → Select environment file  
   - Set "FOODLY API - Production Environment" as active

3. **Start Testing**:
   - Run "🔐 Authentication → Login User (Website)" 
   - Token automatically saved to environment
   - Test all platform endpoints

### What's New in v2 ✨
- ✅ **Production Ready**: Pre-configured for https://api.foodlyapp.ge
- ✅ **All Platforms**: Complete endpoints for Kiosk, Android, iOS, Website
- ✅ **Auto-Token Management**: Login requests automatically store auth tokens
- ✅ **CRUD Operations**: Full create, read, update, delete for spots
- ✅ **Multi-Locale Support**: Examples with ka, en, ru, tr locales
- ✅ **Test Scripts**: Automatic response validation
- ✅ **Public Test Endpoints**: No-auth endpoints for quick testing

### Collection Structure
```
🔐 Authentication
  ├── Login User (Kiosk)
  ├── Login User (Android)  
  ├── Login User (iOS)
  ├── Login User (Website)
  ├── Register New User
  └── Logout User

🧪 Public Test Endpoints
  ├── Test Kiosk Spots
  ├── Test Android Spots
  ├── Test iOS Spots
  └── Test Website

🏢 Kiosk Platform
  ├── Get Kiosk Spots
  └── Get Kiosk Restaurants

📱 Android Platform
  ├── Get Android Spots
  ├── Get Android Restaurants
  └── Create Android Spot

🍎 iOS Platform
  ├── Get iOS Spots
  └── Get iOS Restaurants

🌐 Website Platform
  ├── Get Website Spots
  ├── Get Website Restaurants
  └── Get Single Website Spot
```

---

## 🏗 Technical Architecture

#### Framework & Dependencies
- **Laravel 12.x** - Core framework
- **Laravel Sanctum** - API authentication  
- **astrotomic/laravel-translatable** - Localization support
- **Laravel Forge** - Production deployment

#### 🗄️ Database & Environment
- **Production**: MySQL on Laravel Forge (api_db)
- **Test Database**: api_db_test for testing isolation
- **External Data**: foodlyapp database connection
- **Development**: Laravel Herd environment

#### 📁 Project Structure
```
app/Http/Controllers/Api/
├── AuthController.php                # Multi-platform authentication ✅
├── Kiosk/SpotController.php         # Kiosk spots management ✅
├── Android/SpotController.php       # Android spots management ✅
├── Ios/SpotController.php           # iOS spots management ✅
└── Website/SpotController.php       # Website spots management ✅

app/Http/Resources/Spot/
└── SpotResource.php                 # Spot API response formatting ✅

app/Models/Spot/
└── Spot.php                        # Spot data model with translations ✅

routes/
├── api.php                         # Main authentication routes ✅
└── Api/
    ├── kiosk.php                   # Kiosk platform routes ✅
    ├── android.php                 # Android platform routes ✅
    ├── ios.php                     # iOS platform routes ✅
    └── website.php                 # Website platform routes ✅

tests/Feature/
├── Auth/ApiAuthenticationTest.php  # Authentication testing ✅
└── TokenTest.php                   # Integration testing ✅
```

---

## 🚀 Development Quick Start

### Local Development Setup
```bash
# 1. Clone & Install
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

### Local API Testing

#### Development Authentication
```bash
# Get authentication token (local)
curl -X POST "http://api.foodlyapp.ge.test/api/auth/login" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "email": "gakhokia.david@gmail.com",
       "password": "Paroli_321!",
       "client": "website"
     }'
```

#### Test Local Endpoints
```bash
# Access spots with token (local)
curl -X GET "http://api.foodlyapp.ge.test/api/website/spots" \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"

# Public test endpoints (local, no auth)
curl "http://api.foodlyapp.ge.test/api/website/test"
curl "http://api.foodlyapp.ge.test/api/kiosk/spots/test"
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

---

## � Production Status Summary

**🌟 DEPLOYMENT**: ✅ **LIVE ON FORGE**  
**🔗 API URL**: https://api.foodlyapp.ge  
**🔐 AUTH**: ✅ Sanctum with real user credentials  
**🗄️ DATABASE**: ✅ MySQL production & test environments  
**📱 PLATFORMS**: ✅ All 4 platforms (Kiosk, Android, iOS, Website)  
**🏢 SPOTS API**: ✅ Complete CRUD operations  
**🛣️ ROUTES**: ✅ Unique naming resolved  
**📮 POSTMAN**: ✅ Updated v2 collection ready  
**🧪 TESTING**: ✅ All endpoints verified working  

### Recent Accomplishments (January 2025)
- ✅ **SpotController**: Implemented for all platforms with full CRUD
- ✅ **Route Conflicts**: Resolved unique naming issue for Forge deployment  
- ✅ **Production Deploy**: Successfully deployed and tested on Laravel Forge
- ✅ **Authentication**: Working with real user credentials in production
- ✅ **Documentation**: Complete refresh with production-ready examples
- ✨ **Postman Collection v2**: Comprehensive testing suite with auto-token management
- ✅ **Multi-Locale Support**: Working with ka, en, ru, tr locales

---

*🎯 **Status**: Production Ready | 📅 **Last Updated**: January 16, 2025*  
*🌐 **Live API**: https://api.foodlyapp.ge | 📮 **Postman**: v2 Collection Available*
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
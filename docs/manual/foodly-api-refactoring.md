# FOODLY API Refactoring - Multi-Platform Architecture

## 🎯 მიზანი
FOODLY API-ს refactoring platform-specific endpoints-ებისთვის (Kiosk, Android, iOS) Laravel Sanctum authentication-ითა და locale middleware-ით.

## 📋 Implementation Steps

### Step 1: SetLocale Middleware შექმნა
- [ ] `app/Http/Middleware/SetLocale.php` middleware შექმნა
- [ ] Locale detection from query params და headers
- [ ] Validation against supported locales
- [ ] Response headers დამატება

### Step 2: Platform Controllers შექმნა  
- [ ] `app/Http/Controllers/Api/Kiosk/RestaurantController.php`
- [ ] `app/Http/Controllers/Api/Android/RestaurantController.php`
- [ ] `app/Http/Controllers/Api/Ios/RestaurantController.php`
- [ ] ყველა controller-ში `index()` method minimal JSON response-ით

### Step 3: Platform Routes შექმნა
- [ ] `routes/Api/kiosk.php` 
- [ ] `routes/Api/android.php`
- [ ] `routes/Api/ios.php`
- [ ] ყველა route protected `auth:sanctum` და `setlocale` middleware-ით

### Step 4: Routes Registration
- [ ] `bootstrap/app.php`-ში ახალი route files registration
- [ ] Platform-specific prefixes (`/api/kiosk`, `/api/android`, `/api/ios`)

### Step 5: Configuration
- [ ] `config/app.php`-ში `supported_locales` array
- [ ] Middleware alias registration

### Step 6: Testing
- [ ] `/api/kiosk/restaurants?locale=ka` endpoint test
- [ ] `/api/android/restaurants?locale=en` endpoint test  
- [ ] `/api/ios/restaurants` with Bearer token test
- [ ] Headers validation (`X-App-Locale`)

## 🏗 Directory Structure (Target)
```
app/
  Http/
    Controllers/
      Api/
        Kiosk/
          RestaurantController.php
        Android/
          RestaurantController.php
        Ios/
          RestaurantController.php
    Middleware/
      SetLocale.php

routes/
  Api/
    kiosk.php
    android.php  
    ios.php

config/
  app.php (updated with supported_locales)

bootstrap/
  app.php (updated with route registration)
```

## 📡 API Endpoints (Target)
```
GET /api/kiosk/restaurants
GET /api/android/restaurants  
GET /api/ios/restaurants
```

**ყველა endpoint**:
- ✅ `auth:sanctum` middleware
- ✅ `setlocale` middleware
- ✅ JSON response: `{"status": "success"}`
- ✅ Optional `X-App-Locale` header

## 🔧 Features
- **Multi-platform support**: Platform-specific logic separation
- **Locale management**: Automatic locale detection and setting
- **Authentication**: Sanctum token-based authentication  
- **Clean architecture**: Namespace separation per platform

---

*Status: Planning Complete - Ready for Implementation*
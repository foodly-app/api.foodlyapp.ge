# FOODLY API - Postman Collection Guide

## 📋 Overview

მომზადებულია სრული Postman Collection FOODLY API-ისთვის, რომელიც მოიცავს ყველა endpoint-ს authentication-ით, locale testing-ით და automated testing-ით.

## 📁 Files

- `FOODLY-API-Collection.postman_collection.json` - მთავარი Collection
- `FOODLY-API-Environment.postman_environment.json` - Environment Variables

## 🚀 Setup Instructions

### 1. Import Collection
1. გახსენით Postman
2. File → Import
3. ატვირთეთ `FOODLY-API-Collection.postman_collection.json`

### 2. Import Environment
1. Environments tab-ში
2. Import → ატვირთეთ `FOODLY-API-Environment.postman_environment.json`
3. აირჩიეთ "FOODLY API - Local Environment"

### 3. Environment Variables Setup
```json
{
  "base_url": "http://api.foodlyapp.test",
  "auth_token": "",  // იავტომატურად იფილება login-ის შემდეგ
  "test_user_email": "test@foodlyapp.ge",
  "test_user_password": "password123"
}
```

## 📚 Collection Structure

### 🔐 Authentication
- **Register User** - ახალი user-ის რეგისტრაცია
- **Login User** - Authentication token მიღება (auto-saves token)
- **Get User Profile** - User profile მონაცემები
- **Logout User** - Session-ის დასრულება

### 🔓 Public Test Endpoints
- **Kiosk Test (Georgian)** - Kiosk platform test locale=ka
- **Android Test (English)** - Android platform test locale=en
- **iOS Test (Russian)** - iOS platform test locale=ru
- **Locale Fallback Test** - Unsupported locale fallback testing

### 🔒 Protected Endpoints
- **Kiosk Restaurants** - Authentication required
- **Android Restaurants** - Authentication required
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
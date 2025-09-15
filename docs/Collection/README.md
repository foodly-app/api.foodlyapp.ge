# � FOODLY API Collection - Complete Import Kit

> **Everything you need to quickly import and test the FOODLY API in Postman**

## 📁 Files Overview

### 📋 Collection Files
- **`FOODLY-API-Complete.postman_collection.json`** - Complete collection with all endpoints
  - 🔐 Authentication (Login/Register/Logout)
  - 🧪 Public test endpoints (Kiosk, Android, iOS)
  - 🔒 Protected endpoints with full platform support
  - 📍 Kiosk spots management (Get by slug, restaurants, top 10)
  - 🍽️ Restaurant endpoints for all platforms

### 🌍 Environment Files
- **`FOODLY-API-Locale.postman_environment.json`** - Local development (ka locale)
- **`FOODLY-API-Production.postman_environment.json`** - Production server (en locale)
- **`FOODLY-API-Staging.postman_environment.json`** - Staging/Testing (ka locale)

## 🎯 Quick Import Guide

### Step 1: Import Collection
1. Open Postman
2. Click **Import** button
3. Select `FOODLY-API-Complete.postman_collection.json`
4. Collection will appear with organized folders

### Step 2: Import Environment
Choose one environment based on your needs:
- **Locale** - For local development testing
- **Production** - For live API testing
- **Staging** - For staging environment

1. Click **Import** button again
2. Select desired environment file
3. Set as active environment in top-right dropdown

### Step 3: Test Authentication
1. Go to **🔐 Authentication** folder
2. Run **Login User (Kiosk)** request
3. Token will auto-save to environment
4. All other requests now work automatically!

## 🔧 Environment Variables

### Auto-Set Variables (after login)
- `auth_token` - Bearer token for API calls
- `user_id` - Logged in user ID
- `user_email` - User email address
- `client_type` - Platform type (kiosk/android/ios)

### Manual Configuration
- `base_url` - API server URL
- `locale` - Language code (ka, en, ru, tr)
- `test_user_email` - Test account email
- `test_user_password` - Test account password
- `spot_slug` - Example spot for testing

## 🏢 Platform Support

### Kiosk Platform
- Complete spot management
- Restaurant listings
- Top 10 restaurants by spot
- Georgian locale support

### Android Platform
- Restaurant endpoints
- English locale default

### iOS Platform  
- Restaurant endpoints
- Russian locale support

## 🧪 Testing Features

### Global Scripts
- ⏱️ Response time validation
- 📄 Content-Type checking
- 🔑 Token presence verification
- 📊 Data structure validation

### Test Results
Each request includes automated tests for:
- ✅ Successful response codes
- 📊 Data structure validation
- 🎫 Authentication token handling
- 📍 Platform-specific responses

## 🚦 Usage Examples

### Basic Workflow
1. **Login** → Sets auth token automatically
2. **Get All Spots** → View available locations
3. **Get Spot by Slug** → Detailed spot information
4. **Get Restaurants** → All restaurants in spot
5. **Get Top 10** → Most popular restaurants

### Multi-Platform Testing
Test the same functionality across platforms:
- Kiosk: Georgian locale, touch interface optimized
- Android: English locale, mobile optimized
- iOS: Russian locale, iOS design guidelines

## 📝 Notes

- All protected endpoints require authentication
- Tokens auto-refresh on login
- Locale parameter affects response language
- Collection includes validation tests
- Environment variables auto-populate

## 📞 Support
For detailed setup instructions, see: [`../POSTMAN-IMPORT-GUIDE.md`](../POSTMAN-IMPORT-GUIDE.md)

---
**Ready to import and test! 🎉**

*📅 Last Updated: January 25, 2025*
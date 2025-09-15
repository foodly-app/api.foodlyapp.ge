# 📮 FOODLY API - Postman Collection

> **Ready-to-use Postman collections and environments for FOODLY API testing**

## 📁 Files in this folder

### Environment Files
- **`FOODLY-API-Production.postman_environment.json`** - Production environment
- **`FOODLY-API-Locale.postman_environment.json`** - Local development environment

### Collection Files
The main collection file is located in the parent `docs/` directory:
- **`../FOODLY-API-Collection.postman_collection.json`** - Latest API collection with all endpoints

> **Note**: This collection includes all the latest kiosk spots endpoints and organized folder structure.

## 🚀 Quick Setup

### 1. Import Environment
1. Open Postman
2. Click **Import** → **Upload Files**
3. Select either:
   - `FOODLY-API-Production.postman_environment.json` (for production testing)
   - `FOODLY-API-Locale.postman_environment.json` (for local development)

### 2. Import Collection
1. Navigate to parent folder: `../`
2. Import `FOODLY-API-Collection.postman_collection.json`

### 3. Activate Environment
1. Select imported environment from dropdown (top-right)
2. Verify `base_url` shows correct URL:
   - **Production**: `https://api.foodlyapp.ge`
   - **Local**: `http://localhost:8000`

## 🔧 Environment Comparison

| Variable | Production | Local Development |
|----------|------------|-------------------|
| `base_url` | `https://api.foodlyapp.ge` | `http://localhost:8000` |
| `test_user_email` | `davit@foodlyapp.ge` | `test@foodlyapp.ge` |
| `test_user_password` | `Paroli_321!` | `password123` |
| `spot_slug` | `restaurant` | `test-spot` |
| `locale` | - | `ka` (Georgian) |
| `fallback_locale` | - | `en` (English) |

## 📋 Available Endpoints

### 🔐 Authentication
- User registration
- User login (all platforms)
- User logout

### 🏢 Kiosk Platform
- **📍 Spots**: List, Get by slug, Get restaurants by spot, Top 10 restaurants
- **🍽️ Restaurants**: List all restaurants

### 📱 Mobile Platforms
- **Android Platform**: Restaurant endpoints
- **🍎 iOS Platform**: Restaurant endpoints

### 🧪 Public Test Endpoints
- No authentication required
- Platform health checks

## 🌍 Supported Locales
- **🇬🇪 Georgian** (`ka`) - Default for kiosk
- **🇺🇸 English** (`en`) - Fallback language
- **🇷🇺 Russian** (`ru`) - iOS examples
- **🇹🇷 Turkish** (`tr`) - Available for testing

## 📞 Support
For detailed setup instructions, see: [`../POSTMAN-IMPORT-GUIDE.md`](../POSTMAN-IMPORT-GUIDE.md)

---
*📅 Last Updated: September 15, 2025*
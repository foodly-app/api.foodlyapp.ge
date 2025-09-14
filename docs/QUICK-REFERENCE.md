# 🚀 FOODLY API - Quick Reference Card

> **Production Ready** ✅ | **Version 2.0** | **January 2025**

---

## 🔗 Essential URLs

| Resource | URL |
|----------|-----|
| **🌐 Production API** | `https://api.foodlyapp.ge` |
| **🧪 Health Check** | `https://api.foodlyapp.ge/api/website/test` |
| **📮 Postman Collection** | [`FOODLY-API-v2-Collection.json`](./FOODLY-API-v2-Collection.postman_collection.json) |
| **🌍 Environment File** | [`FOODLY-API-Production.json`](./FOODLY-API-Production.postman_environment.json) |

---

## 🔐 Authentication

### Quick Login (Copy & Paste Ready)
```bash
curl -X POST "https://api.foodlyapp.ge/api/auth/login" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "email": "davit@foodlyapp.ge",
       "password": "11111111",
       "client": "website",
       "device_name": "Quick Test"
     }'
```

### Use Token
```bash
curl -X GET "https://api.foodlyapp.ge/api/website/spots" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json"
```

---

## 📱 Platform Endpoints

### All Platforms Support
| Platform | Base Route | Description |
|----------|-----------|-------------|
| **🏢 Kiosk** | `/api/kiosk/*` | Restaurant terminals |
| **📱 Android** | `/api/android/*` | Mobile app |
| **🍎 iOS** | `/api/ios/*` | iOS app |
| **🌐 Website** | `/api/website/*` | Web application |

### Spots CRUD (Replace `{platform}` with kiosk/android/ios/website)
```
GET    /api/{platform}/spots       - List all spots
GET    /api/{platform}/spots/{id}  - Get single spot  
POST   /api/{platform}/spots       - Create new spot
PUT    /api/{platform}/spots/{id}  - Update spot
DELETE /api/{platform}/spots/{id}  - Delete spot
```

### Restaurants (Read-only)
```
GET /api/{platform}/restaurants - List restaurants
```

---

## 🧪 Quick Tests (No Auth Required)

### Health Checks
```bash
# General API health
curl "https://api.foodlyapp.ge/api/website/test"

# Platform-specific tests  
curl "https://api.foodlyapp.ge/api/kiosk/spots/test"
curl "https://api.foodlyapp.ge/api/android/spots/test"
curl "https://api.foodlyapp.ge/api/ios/spots/test"
```

---

## 🌍 Localization

Add `?locale=XX` to any request:

| Code | Language | Example |
|------|----------|---------|
| `ka` | Georgian | `/api/website/spots?locale=ka` |
| `en` | English | `/api/website/spots?locale=en` |
| `ru` | Russian | `/api/website/spots?locale=ru` |
| `tr` | Turkish | `/api/website/spots?locale=tr` |

---

## 📋 Response Formats

### Success Response
```json
{
  "data": [
    {
      "id": 4,
      "slug": "bagi-vake",
      "status": "active",
      "rank": 2,
      "image": "spots/bagi-vake.jpg",
      "name": "Bagi Vake"
    }
  ]
}
```

### Error Response
```json
{
  "message": "Unauthenticated."
}
```

---

## 📮 Postman Quick Setup

### Import Steps (30 seconds)
1. **Download**: Collection + Environment files from `docs/` folder
2. **Import**: Both files into Postman
3. **Activate**: "FOODLY API - Production Environment"  
4. **Test**: Run "Login User (Website)" → Token auto-saved
5. **Go**: Test any protected endpoint

### Pre-configured Variables ✨
- `base_url` → `https://api.foodlyapp.ge`
- `test_user_email` → `davit@foodlyapp.ge`
- `test_user_password` → `11111111`
- `auth_token` → *(filled automatically)*

---

## 🔧 Create Spot Example

### POST Request Body
```json
{
  "slug": "new-restaurant-spot",
  "status": "active", 
  "rank": 10,
  "image": "spots/new-restaurant.jpg",
  "name": {
    "en": "New Restaurant Spot",
    "ka": "ახალი რესტორნის ადგილი",
    "ru": "Новое место ресторана"
  }
}
```

### Full cURL Command
```bash
curl -X POST "https://api.foodlyapp.ge/api/website/spots" \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "slug": "test-spot",
       "status": "active",
       "rank": 5,
       "image": "spots/test.jpg", 
       "name": {
         "en": "Test Spot",
         "ka": "ტესტ ადგილი"
       }
     }'
```

---

## 🚨 Common Issues & Solutions

### "Unauthenticated" Error
- ✅ **Solution**: Run login request first, use returned token

### "Route not found" Error  
- ✅ **Solution**: Check platform name (kiosk/android/ios/website)

### Token Expired
- ✅ **Solution**: Login again, new token auto-replaces old one

### Wrong Base URL
- ✅ **Solution**: Use `https://api.foodlyapp.ge` (not .test)

---

## 📞 Support Resources

| Resource | Location |
|----------|----------|
| **📚 Full Documentation** | [`README.md`](./README.md) |
| **🔧 Postman Guide** | [`POSTMAN-IMPORT-GUIDE.md`](./POSTMAN-IMPORT-GUIDE.md) |
| **🧪 Testing Guide** | [`manual/testing-guide.md`](./manual/testing-guide.md) |
| **🏢 Spots API Details** | [`manual/spots-api.md`](./manual/spots-api.md) |
| **📝 Release Notes** | [`RELEASE-NOTES.md`](./RELEASE-NOTES.md) |

---

## 🎯 Status Summary

| Component | Status |
|-----------|---------|
| **🌐 Production API** | ✅ Live & Working |
| **🔐 Authentication** | ✅ Tested with real users |
| **📱 All Platforms** | ✅ Kiosk, Android, iOS, Website |
| **🏢 Spots CRUD** | ✅ Complete operations |
| **🌍 Localization** | ✅ 4 languages supported |
| **📮 Postman Collection** | ✅ v2 ready for import |
| **🧪 Testing** | ✅ Public & protected endpoints |
| **📚 Documentation** | ✅ Complete & up-to-date |

---

*⚡ **Quick Start**: Import Postman collection → Login → Test endpoints*  
*🌐 **Production**: https://api.foodlyapp.ge*  
*📅 **Updated**: January 16, 2025*
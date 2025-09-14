# FOODLY API Platform Testing Guide

## 🧪 Testing Platform Endpoints

### 📋 Prerequisites
- Valid Sanctum token from `/api/auth/login`
- Laravel Herd running (endpoints available at `*.test` domains)

### 🎯 Test Endpoints

#### 1. Kiosk Platform
```bash
GET http://api.foodlyapp.test/api/kiosk/restaurants?locale=ka
```

**Headers:**
```
Authorization: Bearer {your-token}
Accept: application/json
Content-Type: application/json
```

**Expected Response:**
```json
{
  "status": "success",
  "platform": "kiosk",
  "locale": "ka",
  "message": "Kiosk restaurants endpoint working"
}
```

**Expected Headers:**
```
X-App-Locale: ka
```

#### 2. Android Platform
```bash
GET http://api.foodlyapp.test/api/android/restaurants?locale=en
```

**Expected Response:**
```json
{
  "status": "success",
  "platform": "android", 
  "locale": "en",
  "message": "Android restaurants endpoint working"
}
```

#### 3. iOS Platform
```bash
GET http://api.foodlyapp.test/api/ios/restaurants
```

**Expected Response:**
```json
{
  "status": "success",
  "platform": "ios",
  "locale": "en",
  "message": "iOS restaurants endpoint working"
}
```

---

## 🔧 cURL Test Commands

### Get Authentication Token First:
```bash
curl -X POST http://api.foodlyapp.test/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

### Test Kiosk with Georgian Locale:
```bash
curl -X GET "http://api.foodlyapp.test/api/kiosk/restaurants?locale=ka" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json" \
  -v
```

### Test Android with English Locale:
```bash
curl -X GET "http://api.foodlyapp.test/api/android/restaurants?locale=en" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json" \
  -v
```

### Test iOS (default locale):
```bash
curl -X GET "http://api.foodlyapp.test/api/ios/restaurants" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json" \
  -v
```

---

## 🌐 Locale Testing Scenarios

### 1. Query Parameter Locale
```bash
# Georgian
?locale=ka → X-App-Locale: ka

# Russian  
?locale=ru → X-App-Locale: ru

# Turkish
?locale=tr → X-App-Locale: tr

# Unsupported locale → fallback to 'en'
?locale=fr → X-App-Locale: en
```

### 2. Accept-Language Header
```bash
curl -H "Accept-Language: ka-GE,ka;q=0.9,en;q=0.8" \
  → X-App-Locale: ka

curl -H "Accept-Language: en-US,en;q=0.9" \
  → X-App-Locale: en
```

### 3. Locale Normalization
```bash
# Input → Normalized
en-US → en
ka-GE → ka  
ru-RU → ru
tr-TR → tr
```

---

## ⚠️ Error Cases

### 401 Unauthorized (Missing/Invalid Token)
```json
{
  "message": "Unauthenticated."
}
```

### 500 Server Error (Middleware Issue)
Check logs for SetLocale middleware errors.

---

## ✅ Success Criteria

1. **Authentication**: All endpoints require valid Sanctum token
2. **Locale Detection**: Query param `?locale=` works correctly  
3. **Locale Headers**: `X-App-Locale` header in response
4. **Platform Separation**: Each platform returns correct platform name
5. **Fallback**: Unsupported locales fallback to 'en'
6. **Route Structure**: Clean URLs `/api/{platform}/restaurants`

---

**All Tests Passing → Multi-Platform API Ready! 🎉**
# Testing Documentation

## 🧪 FOODLY API Testing Guide

### Updated: September 15, 2025

---

## 📋 Test Environment Setup

### Database Configuration
- **Production DB**: `api_db` (MySQL)
- **Test DB**: `api_db_test` (MySQL)  
- **External DB**: `foodlyapp` (source data)

### Environment Files
```bash
# .env (Development)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_db
DB_USERNAME=root
DB_PASSWORD=Admin1.

# .env.testing (Testing)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306  
DB_DATABASE=api_db_test
DB_USERNAME=root
DB_PASSWORD=Admin1.
```

---

## 🔬 Test Cases

### 1. Authentication Tests

**File:** `tests/Feature/Auth/ApiAuthenticationTest.php`

**Tests:**
- User login with valid credentials returns token
- User login with invalid credentials returns error
- Token includes correct client information
- Multiple platform client validation

**Run Command:**
```bash
php artisan test tests/Feature/Auth/ApiAuthenticationTest.php
```

### 2. Token Integration Tests  

**File:** `tests/Feature/TokenTest.php`

**Tests:**
- Complete authentication flow
- Token generation validation
- Protected endpoint access with token
- Real user credential testing

**Sample Output:**
```
🔑 Generated Token: 119|8EHdWh4kRkS2PP7k47itO65LrPHQZ5kE53Jx6brkb2a9e5ce
📧 User Email: gakhokia.david@gmail.com
👤 User Name: David Gakhokia
📱 Client: kiosk
```

**Run Command:**
```bash
php artisan test tests/Feature/TokenTest.php
```

---

## 🎯 Manual Testing Endpoints

### Authentication Testing
```bash
# Login Request
curl -X POST http://api.foodlyapp.ge.test/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "gakhokia.david@gmail.com",
    "password": "Paroli_321!",
    "client": "kiosk"
  }'
```

### Protected Endpoint Testing
```bash
# Access spots with token
curl -X GET http://api.foodlyapp.ge.test/api/kiosk/spots \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Public Test Endpoints
```bash
# Test kiosk endpoint
curl -X GET http://api.foodlyapp.ge.test/api/kiosk/spots/test

# Test all platforms
curl -X GET http://api.foodlyapp.ge.test/api/android/spots/test
curl -X GET http://api.foodlyapp.ge.test/api/ios/spots/test  
curl -X GET http://api.foodlyapp.ge.test/api/website/spots/test
```

---

## 🐛 Common Issues & Solutions

### 1. Database Connection Issues
**Problem:** `SQLSTATE[42S02]: Base table or view not found`

**Solution:**
```bash
# Run migrations
php artisan migrate

# For testing environment  
php artisan migrate --env=testing
```

### 2. Token Authentication Issues
**Problem:** `Unauthenticated` responses

**Check:**
- Token format: `Bearer {token}`
- Token validity (not expired)
- Correct platform abilities in token

### 3. Test Database Issues
**Problem:** Tests fail due to missing data

**Solution:**
```bash
# Migrate test database
php artisan migrate --env=testing

# Seed test data if needed
php artisan db:seed --env=testing
```

---

## 📊 Test Coverage Areas

### ✅ Covered
- Authentication flow (login/register)
- Token generation and validation
- Multi-platform client support
- Protected endpoint access
- Database transactions in tests

### 🔄 In Progress  
- Spots CRUD operations testing
- Locale/internationalization testing
- Error handling edge cases

### 📝 Planned
- Performance testing
- Load testing for high traffic
- Security penetration testing

---

## 🚀 Running All Tests

### Full Test Suite
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test group
php artisan test --group=auth
```

### Test Output Format
```bash
✓ User can get access token (0.42s)
✓ API login with valid credentials returns token (0.23s)
✓ Protected endpoints require authentication (0.18s)

Tests: 3 passed (39 assertions)
Duration: 0.83s
```
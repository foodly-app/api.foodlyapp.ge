# API Documentation - Laravel Sanctum Authentication

## 🔐 Authentication Endpoints

### Base URL
```
http://api.foodlyapp.test/api
```

---

## 📝 Public Endpoints (No Authentication Required)

### 1. Register User
**POST** `/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2025-09-14T10:00:00Z"
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

### 2. Login User
**POST** `/auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "2|xxxxxxxxxxxxxxxx"
  }
}
```

---

## 🔒 Protected Endpoints (Authentication Required)

### Headers
All protected endpoints require:
```
Authorization: Bearer {your-token}
Content-Type: application/json
Accept: application/json
```

### 3. Get User Profile
**GET** `/user/`

**Response (200):**
```json
{
  "status": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "email_verified_at": null,
      "created_at": "2025-09-14T10:00:00Z",
      "updated_at": "2025-09-14T10:00:00Z"
    }
  }
}
```

### 4. Logout User
**POST** `/auth/logout`

**Response (200):**
```json
{
  "status": "success",
  "message": "Logged out successfully"
}
```

### 5. Refresh Token
**POST** `/user/refresh-token`

**Response (200):**
```json
{
  "status": "success",
  "message": "Token refreshed successfully",
  "data": {
    "token": "3|xxxxxxxxxxxxxxxx"
  }
}
```

---

## 🛠 Development/Testing Endpoints

### 6. Test Database Connection
**GET** `/test/db-connection`

### 7. Test Table Structure
**GET** `/test/table/{tableName}`

---

## 📋 Error Responses

### Validation Error (422)
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Authentication Error (401)
```json
{
  "status": "error",
  "message": "Invalid credentials"
}
```

### Server Error (500)
```json
{
  "status": "error",
  "message": "Registration failed",
  "error": "Database connection failed"
}
```

---

## 🚀 Usage Examples

### Using cURL

#### Register:
```bash
curl -X POST http://api.foodlyapp.test/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com", 
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login:
```bash
curl -X POST http://api.foodlyapp.test/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

#### Get User (with token):
```bash
curl -X GET http://api.foodlyapp.test/api/user/ \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## 🔧 Configuration

### Sanctum Config
- **Config file**: `config/sanctum.php`
- **Middleware**: Configured in `bootstrap/app.php`
- **Model**: `User` model with `HasApiTokens` trait

### Token Management
- Tokens are stored in `personal_access_tokens` table
- Tokens don't expire by default (configurable)
- Multiple tokens per user are supported
- Tokens can be revoked individually or all at once

---

**API Ready for Testing!** 🎉
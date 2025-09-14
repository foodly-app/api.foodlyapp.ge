# Database Test Endpoints

## 🎯 მიზანი
Database connection-ის ტესტირება და მონაცემთა ბაზის სტრუქტურის ანალიზი.

## 📋 Available Endpoints

### 1. Database Connection Test
**URL**: `/test/db` (Web) ან `/api/test/db-connection` (API)  
**Method**: GET  
**Description**: ტესტს უკეთებს database connection-ს და აბრუნებს ძირითად ინფორმაციას

**Response Example**:
```json
{
  "status": "success",
  "message": "Database connection successful",
  "data": {
    "database_name": "foodlyapp_db",
    "connection_status": "Connected",
    "user_count": 1,
    "sample_users": [...],
    "tables": [...],
    "timestamp": "2025-09-14T10:00:00Z"
  }
}
```

### 2. Table Structure Analysis
**URL**: `/api/test/table/{tableName}`  
**Method**: GET  
**Description**: კონკრეტული table-ის სტრუქტურის ანალიზი

**Parameters**:
- `tableName` (string) - table-ის სახელი

**Response Example**:
```json
{
  "status": "success",
  "table_name": "users",
  "data": {
    "columns": [...],
    "row_count": 10,
    "sample_data": [...],
    "timestamp": "2025-09-14T10:00:00Z"
  }
}
```

## 🚀 როგორ გავტესტოთ (Laravel Herd)

### Option 1: Browser (Web Route)
```
http://api.foodlyapp.test/test/db
```

### Option 2: API Client (Postman, Insomnia, curl)
```bash
# Connection test
curl http://api.foodlyapp.test/api/test/db-connection

# Table structure test
curl http://api.foodlyapp.test/api/test/table/users
```

### Option 3: Laravel Tinker
```php
php artisan tinker

// Test database connection
DB::connection()->getPdo();

// List all tables (MySQL)
DB::select("SELECT TABLE_NAME as name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE()");
```

## 📂 Files Created

1. **Controller**: `app/Http/Controllers/DatabaseTestController.php`
   - `testConnection()` method
   - `testTableStructure($tableName)` method

2. **Routes**: 
   - `routes/api.php` - API endpoints
   - `routes/web.php` - Browser-accessible endpoint
   - `bootstrap/app.php` - API routes registration

## 🔧 Features

- ✅ Database connection validation (MySQL)
- ✅ Table listing via INFORMATION_SCHEMA
- ✅ Column structure analysis with data types
- ✅ Sample data extraction
- ✅ Error handling
- ✅ JSON responses
- ✅ Both web and API access

---

**Ready to test**: Herd-ით ავტომატურად ხელმისაწვდომია `http://api.foodlyapp.test/test/db`
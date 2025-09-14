# FoodlyApp.ge API - Development Manual

## 📋 პროექტის განვითარების workflow

### 🎯 პროექტის მიზანი
FoodlyApp.ge API - მულტი-ენოვანი food delivery აპლიკაციის backend API

### 🛠 ტექნოლოგიები
- **Framework**: Laravel 12.x
- **PHP**: ^8.2  
- **Frontend**: Livewire + Flux
- **Database**: SQLite (development)
- **Localization**: astrotomic/laravel-translatable

---

## ✅ განხორციელებული ეტაპები

### 1. Laravel Starter Kit Setup 
- ✅ Laravel 12 Livewire Starter Kit დაყენება
- ✅ ძირითადი package-ების კონფიგურაცია
- ✅ Flux UI Components სისტემა

### 2. Translatable Package Setup
- ✅ `astrotomic/laravel-translatable` ^11.16 დაყენება  
- ✅ კონფიგურაციის ფაილის გამოქვეყნება (`config/translatable.php`)
- ✅ მხარდაჭერილი ენების კონფიგურაცია:
  - `en` - ინგლისური (default/fallback)
  - `ka` - ქართული  
  - `ru` - რუსული
  - `tr` - თურქული

### 3. Language Files Structure
- ✅ `lang/` directory შექმნა (Laravel 12 სტანდარტის მიხედვით)
- ✅ ყველა ენისთვის ფოლდერების შექმნა (`en/`, `ka/`, `ru/`, `tr/`)
- ✅ ძირითადი translation ფაილების template-ები

### 4. Documentation Setup  
- ✅ `docs/` ფოლდერის შექმნა
- ✅ `docs/manual/` workflow documentation-ისთვის
- ✅ მიმდინარე manual ფაილის შექმნა

---

## 📋 შემდეგი ეტაპები (დაგეგმილი)

### Phase 1: Database Connection & Data Extraction
- [ ] არსებული მონაცემთა ბაზის კონფიგურაცია
- [ ] Database connection setup
- [ ] მონაცემთა ანალიზი და სტრუქტურის შესწავლა
- [ ] Data extraction tools/scripts შექმნა
- [ ] არსებული ინფორმაციის mapping

### Phase 2: API Architecture
- [ ] Authentication System (Sanctum)
- [ ] Restaurant API Endpoints
- [ ] Menu/Food Items API
- [ ] Order Management API
- [ ] User Management API

### Phase 3: Advanced Features
- [ ] Search & Filtering
- [ ] Geolocation Services
- [ ] Payment Integration
- [ ] Push Notifications
- [ ] Admin Dashboard

### Phase 4: Testing & Deployment
- [ ] Unit Tests სიუტი
- [ ] Feature Tests
- [ ] API Documentation (Swagger/OpenAPI)
- [ ] Production Environment Setup

---

## 📂 პროექტის სტრუქტურა

```
api.foodlyapp.ge/
├── app/
│   ├── Models/           # Eloquent Models
│   ├── Http/Controllers/ # API Controllers
│   └── Livewire/        # Livewire Components
├── config/
│   └── translatable.php # Translatable config
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── docs/
│   └── manual/          # Development documentation
├── lang/                # Language files (Laravel 12 standard)
│   ├── en/
│   ├── ka/
│   ├── ru/
│   └── tr/
└── resources/
    └── views/           # Blade templates
```

---

## 🔧 Development Guidelines

### Code Style
- PSR-12 კოდის სტანდარტი
- Laravel Pint გამოყენება formatting-ისთვის

### Database
- Migration-ები ყველა schema ცვლილებისთვის
- Seeder-ები test data-სთვის
- Model relationships სწორად განსაზღვრა

### API Design
- RESTful API principles
- JSON responses
- Proper HTTP status codes
- API versioning

---

*ბოლო განახლება: 2025-09-14*
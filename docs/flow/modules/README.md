# 🏗️ FOODLY API - Module Architecture Overview

## 📋 Table of Contents
1. [Architecture Overview](#architecture-overview)
2. [Module Structure](#module-structure)
3. [Available Modules](#available-modules)
4. [Implementation Patterns](#implementation-patterns)
5. [Development Guidelines](#development-guidelines)

---

## 🏗️ Architecture Overview

The FOODLY API follows a **modular architecture** where each business domain is organized as a separate module with consistent patterns across all platforms.

### Platform-First Design
```
🏢 Kiosk Platform    (Georgian)     - Restaurant terminal systems
📱 Android Platform  (English)     - Mobile Android application  
🍎 iOS Platform      (Russian)     - iOS mobile application
🌐 Website Platform  (Multi-locale) - Web application
```

### Module Structure Pattern
Each module follows the same organizational pattern:
```
app/
├── Models/{Module}/
│   └── {Module}.php                 # Main model with translations
├── Http/
│   ├── Controllers/Api/
│   │   ├── Kiosk/{Module}Controller.php
│   │   ├── Android/{Module}Controller.php
│   │   ├── Ios/{Module}Controller.php
│   │   └── Website/{Module}Controller.php
│   └── Resources/{Module}/
│       └── {Module}Resource.php     # API response formatting
└── Http/Middleware/
    └── SetLocale.php               # Locale management

routes/Api/
├── kiosk.php                       # Kiosk platform routes
├── android.php                     # Android platform routes  
├── ios.php                         # iOS platform routes
└── website.php                     # Website platform routes

docs/flow/modules/{module}/
└── {MODULE}-MODULE-INSTRUCTIONS.md # Complete module documentation
```

---

## 📂 Module Structure

### Current Directory Organization
```
docs/
├── flow/
│   └── modules/
│       ├── spots/
│       │   └── SPOTS-MODULE-INSTRUCTIONS.md
│       ├── spaces/
│       │   └── SPACES-MODULE-INSTRUCTIONS.md
│       ├── cuisines/
│       │   └── CUISINES-MODULE-INSTRUCTIONS.md
│       ├── dishes/
│       │   └── DISHES-MODULE-INSTRUCTIONS.md
│       └── README.md (this file)
├── Collection/                     # Postman collections
├── manual/                         # Manual documentation
└── *.md                           # General API docs
```

---

## 🎯 Available Modules

### 📍 Spots Module
**Purpose**: Location-based restaurant discovery by geographic spots  
**File**: [`docs/flow/modules/spots/SPOTS-MODULE-INSTRUCTIONS.md`](./spots/SPOTS-MODULE-INSTRUCTIONS.md)

**Key Features:**
- Multi-platform support (Kiosk, Android, iOS, Website)
- Translatable spot names (Georgian, English, Russian, Turkish)
- Restaurant associations with ranking
- Slug-based SEO-friendly URLs
- Pagination and status management

**Endpoints:**
```
GET /api/{platform}/spots
GET /api/{platform}/spots/{slug}
GET /api/{platform}/spots/{slug}/restaurants
GET /api/{platform}/spots/{slug}/restaurants/top10
```

### 🌌 Spaces Module  
**Purpose**: Restaurant discovery by dining space types  
**File**: [`docs/flow/modules/spaces/SPACES-MODULE-INSTRUCTIONS.md`](./spaces/SPACES-MODULE-INSTRUCTIONS.md)

**Key Features:**
- Same architecture as Spots module
- Dining space categorization (outdoor, rooftop, garden, etc.)
- Identical API structure for consistency
- Cross-platform compatibility

**Endpoints:**
```
GET /api/{platform}/spaces
GET /api/{platform}/spaces/{slug}
GET /api/{platform}/spaces/{slug}/restaurants
GET /api/{platform}/spaces/{slug}/restaurants/top10
```

### 🍽️ Cuisines Module  
**Purpose**: Restaurant discovery by cuisine type and culinary style  
**File**: [`docs/flow/modules/cuisines/CUISINES-MODULE-INSTRUCTIONS.md`](./cuisines/CUISINES-MODULE-INSTRUCTIONS.md)

**Key Features:**
- Same architecture as Spots and Spaces modules
- Cuisine categorization (Italian, Georgian, Asian, Mediterranean, etc.)
- Food preference-based restaurant filtering
- Consistent API structure across all platforms

**Endpoints:**
```
GET /api/{platform}/cuisines
GET /api/{platform}/cuisines/{slug}
GET /api/{platform}/cuisines/{slug}/restaurants
GET /api/{platform}/cuisines/{slug}/restaurants/top10
```

### 🍛 Dishes Module  
**Purpose**: Restaurant discovery by specific dishes and menu items  
**File**: [`docs/flow/modules/dishes/DISHES-MODULE-INSTRUCTIONS.md`](./dishes/DISHES-MODULE-INSTRUCTIONS.md)

**Key Features:**
- Dish-based restaurant discovery
- Rich dish descriptions with translations
- Restaurant associations with ranking
- Currently implemented for Website platform only
- Consistent API structure for future platform expansion

**Endpoints:**
```
GET /api/website/dishes
GET /api/website/dishes/{slug}
GET /api/website/dishes/{slug}/restaurants
GET /api/website/dishes/{slug}/restaurants/top10
```

---

## 🛠️ Implementation Patterns

### 1. Controller Pattern
All modules follow the same controller structure:

```php
class {Module}Controller extends Controller
{
    public function index(Request $request)           // List all items
    public function showBySlug($slug)                 // Get single item
    public function restaurantsByModule($slug)        // Get restaurants
    public function top10RestaurantsByModule($slug)   // Get top 10 restaurants
    public function test(Request $request)            // Test endpoint
}
```

### 2. Model Pattern
All modules use translatable models:

```php
class {Module} extends Model
{
    use Translatable;
    
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';
    
    protected $fillable = ['slug', 'rank', 'image', 'image_link', 'status'];
    public $translatedAttributes = ['name'];
    
    public function restaurants() {
        return $this->belongsToMany(Restaurant::class)
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}
```

### 3. Resource Pattern
Consistent API response formatting:

```php
class {Module}Resource extends JsonResource
{
    public function toArray($request) {
        $locale = $request->query('locale');
        
        if ($locale) {
            app()->setLocale($locale);
            return [
                'id' => $this->id,
                'slug' => $this->slug,
                'name' => $this->name,  // Auto-translated
                'status' => $this->status,
                'image' => $this->image,
            ];
        }
        
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'status' => $this->status,
            'translations' => $this->translations
        ];
    }
}
```

### 4. Route Pattern
Platform-specific route organization:

```php
// Protected routes (auth required for kiosk/android/ios)
Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::prefix('{modules}')
        ->name('{platform}.{modules}.')
        ->controller({Module}Controller::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{slug}', 'showBySlug');
            Route::get('/{slug}/restaurants', 'restaurantsBy{Module}');
            Route::get('/{slug}/restaurants/top10', 'top10RestaurantsBy{Module}');
        });
});
```

---

## 📋 Development Guidelines

### Adding New Modules

#### 1. Create Module Structure
```bash
# Create directories
mkdir -p app/Models/{Module}
mkdir -p app/Http/Controllers/Api/Kiosk
mkdir -p app/Http/Controllers/Api/Android  
mkdir -p app/Http/Controllers/Api/Ios
mkdir -p app/Http/Controllers/Api/Website
mkdir -p app/Http/Resources/{Module}
mkdir -p docs/flow/modules/{module}
```

#### 2. Follow Naming Conventions
- **Models**: `{Module}.php` (e.g., `Spot.php`, `Space.php`)
- **Controllers**: `{Module}Controller.php` (e.g., `SpotController.php`)
- **Resources**: `{Module}Resource.php` (e.g., `SpotResource.php`)
- **Documentation**: `{MODULE}-MODULE-INSTRUCTIONS.md`

#### 3. Implement Core Features
- ✅ Translatable model with status management
- ✅ Four platform controllers (Kiosk, Android, iOS, Website)
- ✅ API resource for response formatting
- ✅ Route definitions for all platforms
- ✅ Comprehensive documentation

#### 4. Database Structure
Each module requires:
- Main table: `{modules}` (e.g., `spots`, `spaces`)
- Translation table: `{module}_translations`
- Pivot table: `restaurant_{module}` (e.g., `restaurant_spot`)

#### 5. Testing Integration
- Add Postman collection sections for all platforms
- Include test endpoints for each platform
- Environment variables setup
- Error scenario testing

---

## 🎯 Best Practices

### Code Organization
- Keep controllers thin - business logic in models/services
- Use consistent naming across all modules
- Follow Laravel conventions and PSR standards
- Implement proper error handling and validation

### API Design
- Maintain consistent endpoint patterns
- Use HTTP status codes correctly
- Provide meaningful error messages
- Support pagination for list endpoints

### Documentation
- Keep module documentation up to date
- Include real request/response examples
- Document all error scenarios
- Provide implementation guides

### Internationalization
- Support all required locales (ka, en, ru, tr)
- Use translatable models consistently
- Provide locale-specific responses
- Handle missing translations gracefully

---

## 📚 Additional Resources

### Related Documentation
- [API Main Documentation](../../README.md)
- [Quick Reference Guide](../../QUICK-REFERENCE.md)
- [Postman Import Guide](../../POSTMAN-IMPORT-GUIDE.md)

### Module-Specific Docs
- [Spots Module Instructions](./spots/SPOTS-MODULE-INSTRUCTIONS.md)
- [Spaces Module Instructions](./spaces/SPACES-MODULE-INSTRUCTIONS.md)
- [Cuisines Module Instructions](./cuisines/CUISINES-MODULE-INSTRUCTIONS.md)

### Development Tools
- [Postman Collections](../../Collection/)
- [Manual Documentation](../../manual/)

---

## 🎉 Summary

This modular architecture provides:

✅ **Scalable Structure** - Easy to add new modules  
✅ **Consistent Patterns** - Same approach across all modules  
✅ **Platform Support** - Multi-platform from day one  
✅ **Internationalization** - Built-in translation support  
✅ **Comprehensive Documentation** - Complete guides for each module  
✅ **Testing Ready** - Postman collections and test endpoints  

The architecture ensures maintainability, consistency, and scalability for the FOODLY API ecosystem.
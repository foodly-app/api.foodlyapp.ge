# FoodlyApp.ge API Documentation

## 📖 დოკუმენტაცია

### Manual
- [Development Workflow](./manual/README.md) - განვითარების workflow და ეტაპები

### Quick Start
1. Clone repository
2. Run `composer install`
3. Setup `.env` file
4. Run `php artisan migrate`
5. Start development server: `php artisan serve`

### Project Overview
მულტი-ენოვანი food delivery API Laravel 12 ბაზაზე, რომელიც მხარს უჭერს ქართულ, ინგლისურ, რუსულ და თურქულ ენებს.

### Supported Languages
- 🇬🇧 English (en) - Default
- 🇬🇪 Georgian (ka)  
- 🇷🇺 Russian (ru)
- 🇹🇷 Turkish (tr)

### Tech Stack
- Laravel 12.x
- Livewire + Flux
- astrotomic/laravel-translatable
- SQLite (dev) / MySQL (prod)
<?php

namespace App\Models\Dish;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Restaurant\Restaurant;


class Dish extends Model
{
    use Translatable;

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'slug',
        'rank',
        'image',
        'image_link',
        'status',
    ];

    public $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            static::STATUS_ACTIVE => 'Active',
            static::STATUS_INACTIVE => 'Inactive',
            static::STATUS_MAINTENANCE => 'Maintenance',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return static::getStatuses()[$this->status] ?? 'Unknown';
    }


    // 2. 📎 მოდელს უკავშირებ თარგმანის კლასს
    // public function menuCategories()
    // {
    //     return $this->hasMany(MenuCategory::class);
    // }


    // public function menuItems()
    // {
    //     return $this->hasMany(MenuItem::class);
    // }

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_dish')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}

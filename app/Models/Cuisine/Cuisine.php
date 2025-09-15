<?php

namespace App\Models\Cuisine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Translatable;
use App\Models\Restaurant\Restaurant;

class Cuisine extends Model
{
    use HasFactory, Translatable;
    
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Cuisine\CuisineFactory::new();
    }
    

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

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_cuisine')
            ->withTimestamps();
    }
}
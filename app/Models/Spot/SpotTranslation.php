<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Restaurant\Restaurant;

class Spot extends Model
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
        return $this->belongsToMany(Restaurant::class)
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }
}

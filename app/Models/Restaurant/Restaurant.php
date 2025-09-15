<?php

namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cuisine\Cuisine;
use App\Models\Spot\Spot;
use App\Models\Dish\Dish;
use App\Models\Space\Space;
use App\Models\City\City;

class Restaurant extends Model
{
    use HasFactory, Translatable;

    // Translatable attributes
    public $translatedAttributes = ['name', 'description', 'address'];

    // Fillable attributes
    protected $fillable = [
        'slug',
        'status',
        'rank',
        'logo',
        'image',
        'video',
        'phone',
        'email',
        'whatsapp',
        'website',
        'discount_rate',
        'latitude',
        'longitude',
        'map_link',
        'map_embed_link',
        'time_zone',
        'price_per_person',
        'price_currency',
        'working_hours',
        'delivery_time',
        'reservation_type',
        'qr_code_image',
        'qr_code_link',
        'created_by',
        'updated_by',
        'version',
    ];

    // Cast attributes to appropriate types
    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'discount_rate' => 'integer',
        'delivery_time' => 'integer',
        'version' => 'integer',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_PENDING => 'Pending',
        ];
    }

    /**
     * Get status label attribute
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? 'Unknown';
    }

    /**
     * Scope for active restaurants
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for ordered by rank
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('rank', 'asc');
    }

    /**
     * Relationships
     */

    // Many-to-many relationships
    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class, 'restaurant_cuisine')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }

    public function spots()
    {
        return $this->belongsToMany(Spot::class, 'restaurant_spot')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'restaurant_dish')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }

    public function spaces()
    {
        return $this->belongsToMany(Space::class, 'restaurant_space')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_restaurant')
            ->withPivot(['rank', 'status'])
            ->withTimestamps();
    }


}

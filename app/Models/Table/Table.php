<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Restaurant\Restaurant;
use App\Models\Place\Place;

class Table extends Model
{
    use HasFactory, Translatable;

    // Translatable attributes
    public $translatedAttributes = ['name', 'description'];

    // Status constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_RESERVED = 2;
    const STATUS_MAINTENANCE = 3;

    // Fillable attributes
    protected $fillable = [
        'slug',
        'restaurant_id',
        'place_id',
        'status',
        'icon',
        'image',
        'image_link',
        'seats',
        'capacity',
        'latitude',
        'longitude',
        'rank',
        'qr_code_image',
        'qr_code_link',
        'qr_code_url',
        'created_by',
        'updated_by',
    ];

    // Cast attributes
    protected $casts = [
        'status' => 'integer',
        'seats' => 'integer',
        'capacity' => 'integer',
        'rank' => 'integer',
        'restaurant_id' => 'integer',
        'place_id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($table) {
            if (Auth::check()) {
                $table->created_by = Auth::id();
            }
        });

        static::updating(function ($table) {
            if (Auth::check()) {
                $table->updated_by = Auth::id();
            }
        });

        static::saved(function ($table) {
            if (empty($table->slug)) {
                $table->slug = $table->generateSlug();
                $table->saveQuietly();
            }
        });
    }

    /**
     * Relationships
     */

    /**
     * Get the restaurant that owns the table
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the place that owns the table
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Query Scopes
     */

    /**
     * Scope to get only active tables
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get available tables (not reserved)
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE]);
    }

    /**
     * Scope to get tables by restaurant
     */
    public function scopeByRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    /**
     * Scope to get tables by place
     */
    public function scopeByPlace($query, $placeId)
    {
        return $query->where('place_id', $placeId);
    }

    /**
     * Scope to order by rank
     */
    public function scopeOrderByRank($query, $direction = 'asc')
    {
        return $query->orderBy('rank', $direction);
    }

    /**
     * Scope to filter by capacity
     */
    public function scopeByCapacity($query, $minCapacity = null, $maxCapacity = null)
    {
        if ($minCapacity) {
            $query->where('capacity', '>=', $minCapacity);
        }
        if ($maxCapacity) {
            $query->where('capacity', '<=', $maxCapacity);
        }
        return $query;
    }

    /**
     * Accessors
     */

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName(): string
    {
        $request = request();
        
        // Check if we're in an admin context or API context
        if ($request && ($request->is('admin/*') || $request->is('api/admin/*'))) {
            return 'id';
        }
        
        return 'slug';
    }

    /**
     * Check if table is active
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if table is available
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if table is reserved
     */
    public function getIsReservedAttribute(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    /**
     * Get full image URL
     */
    public function getFullImageUrlAttribute(): ?string
    {
        if ($this->image_link) {
            return $this->image_link;
        }
        
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }

    /**
     * Get QR code full URL
     */
    public function getFullQrCodeUrlAttribute(): ?string
    {
        if ($this->qr_code_link) {
            return $this->qr_code_link;
        }
        
        if ($this->qr_code_image) {
            return asset('storage/' . $this->qr_code_image);
        }
        
        return null;
    }

    /**
     * Get the capacity attribute (use seats if capacity doesn't exist)
     */
    public function getCapacityAttribute()
    {
        return $this->attributes['capacity'] ?? $this->attributes['seats'] ?? null;
    }

    /**
     * Set the capacity attribute (also set seats for backward compatibility)
     */
    public function setCapacityAttribute($value)
    {
        $this->attributes['capacity'] = $value;
        $this->attributes['seats'] = $value;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_RESERVED => 'Reserved',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Unknown',
        };
    }

    /**
     * Methods
     */

    /**
     * Generate slug from name
     */
    public function generateSlug(): string
    {
        $name = $this->getTranslation('name', 'en') ?? $this->getTranslation('name', 'ka') ?? 'table';
        return Str::slug($name) . '-' . $this->id;
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_RESERVED => 'Reserved',
            self::STATUS_MAINTENANCE => 'Maintenance',
        ];
    }
}
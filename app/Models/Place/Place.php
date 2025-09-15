<?php

namespace App\Models\Place;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Restaurant\Restaurant;
use App\Models\Table\Table;

class Place extends Model
{
    use HasFactory, Translatable;

    // Translatable attributes
    public $translatedAttributes = ['name', 'description'];

    // Status constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    // Fillable attributes
    protected $fillable = [
        'restaurant_id',
        'slug',
        'image',
        'image_link',
        'rank',
        'status',
        'qr_code',
        'qr_code_image',
        'qr_code_link',
    ];

    // Cast attributes
    protected $casts = [
        'status' => 'integer',
        'rank' => 'integer',
        'restaurant_id' => 'integer',
    ];

    /**
     * Relationships
     */

    /**
     * Get the restaurant that owns the place
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get tables for this place
     */
    public function tables()
    {
        return $this->hasMany(Table::class)->active()->orderBy('rank', 'asc');
    }

    /**
     * Query Scopes
     */

    /**
     * Scope to get only active places
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get places by restaurant
     */
    public function scopeByRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    /**
     * Scope to order by rank
     */
    public function scopeOrderByRank($query, $direction = 'asc')
    {
        return $query->orderBy('rank', $direction);
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
     * Check if place is active
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
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
     * Methods
     */

    /**
     * Generate slug from name
     */
    public function generateSlug(): string
    {
        $name = $this->getTranslation('name', 'en') ?? $this->getTranslation('name', 'ka') ?? 'place';
        return Str::slug($name) . '-' . $this->id;
    }

    /**
     * Auto-generate slug on save if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($place) {
            if (empty($place->slug)) {
                $place->slug = $place->generateSlug();
                $place->saveQuietly();
            }
        });
    }
}
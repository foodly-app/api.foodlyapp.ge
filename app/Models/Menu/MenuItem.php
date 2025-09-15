<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Restaurant\Restaurant;

class MenuItem extends Model
{
    use HasFactory, Translatable;

    // Translatable attributes
    public $translatedAttributes = ['name', 'description', 'ingredients', 'allergens'];

    // Fillable attributes
    protected $fillable = [
        'restaurant_id',
        'menu_category_id',
        'slug',
        'price',
        'discounted_price',
        'unit',
        'quantity',
        'image',
        'image_link',
        'rank',
        'status',
        'available',
        'is_vegan',
        'is_gluten_free',
        'calories',
        'preparation_time',
    ];

    // Cast attributes to appropriate types
    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'available' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'calories' => 'integer',
        'preparation_time' => 'integer',
        'rank' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DRAFT = 'draft';

    /**
     * Unit constants
     */
    const UNIT_PIECE = 'piece';
    const UNIT_GRAM = 'gram';
    const UNIT_KILOGRAM = 'kilogram';
    const UNIT_LITER = 'liter';
    const UNIT_MILLILITER = 'milliliter';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DRAFT => 'Draft',
        ];
    }

    /**
     * Get all available units
     */
    public static function getUnits(): array
    {
        return [
            self::UNIT_PIECE => 'Piece',
            self::UNIT_GRAM => 'Gram',
            self::UNIT_KILOGRAM => 'Kilogram',
            self::UNIT_LITER => 'Liter',
            self::UNIT_MILLILITER => 'Milliliter',
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
     * Get unit label attribute
     */
    public function getUnitLabelAttribute(): string
    {
        return self::getUnits()[$this->unit] ?? 'Unknown';
    }

    /**
     * Get discounted price or regular price
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->discounted_price ?? $this->price;
    }

    /**
     * Check if item has discount
     */
    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->discounted_price) && $this->discounted_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->has_discount) {
            return null;
        }

        return round((($this->price - $this->discounted_price) / $this->price) * 100);
    }

    /**
     * Scope for active menu items
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for available menu items
     */
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    /**
     * Scope for ordered by rank
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('rank', 'asc');
    }

    /**
     * Scope for vegan items
     */
    public function scopeVegan($query)
    {
        return $query->where('is_vegan', true);
    }

    /**
     * Scope for gluten-free items
     */
    public function scopeGlutenFree($query)
    {
        return $query->where('is_gluten_free', true);
    }

    /**
     * Scope for items with discount
     */
    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discounted_price')
            ->whereColumn('discounted_price', '<', 'price');
    }

    /**
     * Relationships
     */

    // Belongs to Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Belongs to MenuCategory
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    // Alias for compatibility
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
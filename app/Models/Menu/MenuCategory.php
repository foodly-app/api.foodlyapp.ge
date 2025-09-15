<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Restaurant\Restaurant;
use App\Models\Dish\Dish;

class MenuCategory extends Model
{
    use HasFactory, Translatable;

    // Translatable attributes
    public $translatedAttributes = ['name', 'description'];

    // Fillable attributes
    protected $fillable = [
        'restaurant_id',
        'parent_id',
        'dish_id',
        'rank',
        'slug',
        'image',
        'image_link',
        'icon',
        'icon_link',
        'status',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
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
     * Scope for active menu categories
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

    // Belongs to Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Belongs to Dish (optional)
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    // Self-referencing relationship for parent category
    public function parent()
    {
        return $this->belongsTo(MenuCategory::class, 'parent_id');
    }

    // Self-referencing relationship for child categories
    public function children()
    {
        return $this->hasMany(MenuCategory::class, 'parent_id')
            ->where('status', self::STATUS_ACTIVE)
            ->orderBy('rank', 'asc');
    }

    // Get all descendants (children, grandchildren, etc.)
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Relationship to MenuItems
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_category_id')
            ->where('status', MenuItem::STATUS_ACTIVE)
            ->where('available', true)
            ->orderBy('rank', 'asc');
    }

    /**
     * Helper methods
     */

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}

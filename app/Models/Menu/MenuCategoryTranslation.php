<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class MenuCategoryTranslation extends Model
{
    // Disable timestamps for translation table
    public $timestamps = false;

    // Fillable attributes
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relationship to the main MenuCategory model
     */
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
}
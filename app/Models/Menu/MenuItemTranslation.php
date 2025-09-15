<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    // Disable timestamps for translation table
    public $timestamps = false;

    // Fillable attributes
    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'allergens'
    ];

    /**
     * Relationship to the main MenuItem model
     */
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
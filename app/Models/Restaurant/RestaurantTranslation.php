<?php

namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Model;

class RestaurantTranslation extends Model
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'address',
    ];

    /**
     * Get the restaurant that owns the translation.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}

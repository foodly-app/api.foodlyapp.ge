<?php

namespace App\Models\Place;

use Illuminate\Database\Eloquent\Model;

class PlaceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];
}
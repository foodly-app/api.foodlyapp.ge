<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
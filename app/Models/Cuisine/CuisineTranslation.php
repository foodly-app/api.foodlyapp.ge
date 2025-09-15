<?php

namespace App\Models\Cuisine;

use Illuminate\Database\Eloquent\Model;

class CuisineTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_desc',
    ];
}

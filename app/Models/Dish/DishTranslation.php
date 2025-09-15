<?php

namespace App\Models\Dish;

use Illuminate\Database\Eloquent\Model;

class DishTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
    ];
}

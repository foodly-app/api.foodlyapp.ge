<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class SpotTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
    ];
}
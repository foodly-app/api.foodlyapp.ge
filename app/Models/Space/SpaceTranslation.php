<?php

namespace App\Models\Space;

use Illuminate\Database\Eloquent\Model;

class SpaceTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
    ];
}

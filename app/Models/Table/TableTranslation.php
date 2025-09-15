<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Model;

class TableTranslation extends Model
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
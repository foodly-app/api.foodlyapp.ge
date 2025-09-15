<?php

namespace App\Models\Reservation;

use App\Models\Place\Place;
use Illuminate\Database\Eloquent\Model;

class PlaceReservationSlot extends Model
{
    protected $fillable = [
        'place_id',
        'day_of_week',
        'time_from',
        'time_to',
        'slot_interval_minutes',
        'available',
    ];

    protected $casts = [
        'available' => 'boolean',
        'day_of_week' => 'integer',
        'slot_interval_minutes' => 'integer',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
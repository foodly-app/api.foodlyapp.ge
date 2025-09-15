<?php

namespace App\Models\Reservation;

use App\Models\Restaurant\Restaurant;
use Illuminate\Database\Eloquent\Model;

class RestaurantReservationSlot extends Model
{
    protected $table = 'restaurant_reservation_slots';

    public $timestamps = true;

    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'time_from',
        'time_to',
        'slot_interval_minutes',
        'available'
    ];

    protected $casts = [
        'available' => 'boolean'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}

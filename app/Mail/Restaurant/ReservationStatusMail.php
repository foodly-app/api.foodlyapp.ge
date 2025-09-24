<?php

namespace App\Mail\Restaurant;

use App\Models\Reservation\Reservation;

class ReservationStatusMail extends \App\Mail\ReservationStatusMail
{
    public function __construct(Reservation $reservation, string $status)
    {
        parent::__construct($reservation, $status, 'restaurant');
    }
}

<?php

namespace App\Mail\Restaurant;

use App\Models\Reservation\Reservation;

class ReservationCreatedMail extends \App\Mail\ReservationCreatedMail
{
    public function __construct(Reservation $reservation)
    {
        $this->recipientType = 'restaurant';
        parent::__construct($reservation);
    }
}

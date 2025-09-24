<?php

namespace App\Mail\Client;

use App\Models\Reservation\Reservation;

class ReservationCreatedMail extends \App\Mail\ReservationCreatedMail
{
    public function __construct(Reservation $reservation)
    {
        $this->recipientType = 'client';
        parent::__construct($reservation);
    }
}

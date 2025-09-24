<?php

namespace App\Mail\Administrator;

use App\Models\Reservation\Reservation;

class ReservationCreatedMail extends \App\Mail\ReservationCreatedMail
{
    public function __construct(Reservation $reservation)
    {
        $this->recipientType = 'administrator';
        parent::__construct($reservation);
    }
}

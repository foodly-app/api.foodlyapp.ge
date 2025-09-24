<?php

namespace App\Listeners;

use App\Events\ReservationStatusChanged;
use App\Mail\ReservationCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedNotification
{
    public function handle(ReservationStatusChanged $event): void
    {
        // Only send emails for newly created (Pending) reservations
        if ($event->newStatus->value !== 'pending') {
            return;
        }

        $reservation = $event->reservation;

        if (empty($reservation->email)) {
            return;
        }

        // Send the reservation created email (sync for now)
        Mail::to($reservation->email)->send(new ReservationCreatedMail($reservation));
    }
}

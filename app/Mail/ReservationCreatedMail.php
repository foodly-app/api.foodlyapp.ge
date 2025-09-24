<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation\Reservation;

class ReservationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;
    public string $recipientType = 'client';

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        // Default behavior: try to pick a client/restaurant/admin specific template named CreatedMail or ConfirmedMail
        $recipientType = property_exists($this, 'recipientType') ? $this->recipientType : 'client';
        $candidate = "emails.{$recipientType}.ConfirmedMail";

        $view = $candidate;
        if (!view()->exists($view)) {
            $view = 'emails.reservation_created';
        }

        return $this->subject(__('emails.subjects.created'))
            ->view($view)
            ->with(['reservation' => $this->reservation]);
    }
}

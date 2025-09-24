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

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('თქვენი ჯავშანი მიღებულია')
            ->view('emails.reservation_created')
            ->with(['reservation' => $this->reservation]);
    }
}

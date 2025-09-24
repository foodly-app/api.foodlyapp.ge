<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation\Reservation;

class ReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;
    public string $status;
    public string $recipientType;

    public function __construct(Reservation $reservation, string $status, string $recipientType = 'client')
    {
        $this->reservation = $reservation;
        $this->status = $status;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $view = 'emails.reservation_status';
        return $this->subject('Reservation update: ' . strtoupper($this->status))
            ->view($view)
            ->with([
                'reservation' => $this->reservation,
                'status' => $this->status,
                'recipientType' => $this->recipientType,
            ]);
    }
}

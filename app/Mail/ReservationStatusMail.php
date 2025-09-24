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
        // Resolve view by recipient type and status. Example: emails/client/ConfirmedMail
        $statusKey = ucfirst($this->status) . 'Mail';
        $candidate = "emails.{$this->recipientType}.{$statusKey}";

        // Fallbacks: prefer specific template, then generic reservation_status, then reservation_created
        $view = $candidate;
        if (!view()->exists($view)) {
            if (view()->exists('emails.reservation_status')) {
                $view = 'emails.reservation_status';
            } else {
                $view = 'emails.reservation_created';
            }
        }

        // Normalize status for a human-friendly subject (handle enum or raw string)
        $statusLabel = $this->status;
        if (is_object($statusLabel) && property_exists($statusLabel, 'value')) {
            $statusLabel = $statusLabel->value;
        }
        if (is_string($statusLabel)) {
            $statusLabel = ucwords(str_replace(['_', '-'], ' ', $statusLabel));
        }

        $subject = __('emails.subjects.status_update', ['status' => $statusLabel]);
        if ($subject === 'emails.subjects.status_update') {
            // translation not found, fallback to a simple subject
            $subject = 'Reservation update: ' . $statusLabel;
        }

        return $this->subject($subject)
            ->view($view)
            ->with([
                'reservation' => $this->reservation,
                'status' => $this->status,
                'recipientType' => $this->recipientType,
            ]);
    }
}

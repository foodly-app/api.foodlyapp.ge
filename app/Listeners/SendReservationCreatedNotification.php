<?php

namespace App\Listeners;

use App\Events\ReservationStatusChanged;
use App\Mail\ReservationStatusMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Handle the event.
     */
    public function handle(ReservationStatusChanged $event): void
    {
        $reservation = $event->reservation;
        $status = $event->newStatus?->value ?? null;

        if (!$status) {
            return;
        }

        // Collect recipients: client, restaurant manager (if any), admin
        $recipients = [];

        if (!empty($reservation->email)) {
            $recipients['client'] = $reservation->email;
        }

        // Resolve manager email from reservable or related restaurant
        $managerEmail = $this->resolveManagerEmail($reservation);
        if ($managerEmail) {
            $recipients['restaurant'] = $managerEmail;
        }

        // Admin email from config
        $adminEmail = config('reservation.admin_email', 'reservation@foodlyapp.io');
        if ($adminEmail) {
            $recipients['admin'] = $adminEmail;
        }

        if (empty($recipients)) {
            Log::warning('ReservationStatusChanged: no recipients for reservation', ['reservation_id' => $reservation->id, 'status' => $status]);
            return;
        }

        foreach ($recipients as $type => $email) {
            try {
                Mail::to($email)->queue(new ReservationStatusMail($reservation, $status, $type));
            } catch (\Throwable $e) {
                Log::error('Failed to queue reservation status mail', ['reservation_id' => $reservation->id, 'email' => $email, 'error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Try to find a manager email for the reservation's reservable
     */
    protected function resolveManagerEmail($reservation): ?string
    {
        $reservable = $reservation->reservable;
        if (!$reservable) return null;

        // Common attribute names fallback
        foreach (['manager_email', 'email', 'contact_email'] as $attr) {
            if (!empty($reservable->{$attr})) return $reservable->{$attr};
        }

        // If reservable has relation to restaurant, try that
        if (method_exists($reservable, 'restaurant')) {
            $restaurant = $reservable->restaurant;
            if ($restaurant) {
                foreach (['manager_email', 'email', 'contact_email'] as $attr) {
                    if (!empty($restaurant->{$attr})) return $restaurant->{$attr};
                }
            }
        }

        return null;
    }
}

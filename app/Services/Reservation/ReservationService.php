<?php

namespace App\Services\Reservation;

use App\Models\Reservation\Reservation;
use App\Events\ReservationStatusChanged;
use App\Enums\ReservationStatus;
use Carbon\Carbon;

class ReservationService
{
    public function createReservation($model, string $reservationDate, string $reservationTime, int $guestsCount, array $customerData)
    {
        $timeFrom = Carbon::createFromFormat('H:i', $reservationTime);
        $slotIntervalMinutes = 60; // Default, მაგრამ მომავალში მოდელიდანაც ამოვიღებთ.
        $timeTo = $timeFrom->copy()->addMinutes($slotIntervalMinutes);

        $reservation = Reservation::create([
            'type' => strtolower(class_basename($model)),
            'reservable_type' => get_class($model),
            'reservable_id' => $model->id,
            'reservation_date' => $reservationDate,
            'time_from' => $timeFrom->format('H:i:s'),
            'time_to' => $timeTo->format('H:i:s'),
            'guests_count' => $guestsCount,
            'name' => $customerData['name'],
            'phone' => $customerData['phone'],
            'email' => $customerData['email'] ?? null,
            'promo_code' => $customerData['promo_code'] ?? null,
            'notes' => $customerData['notes'] ?? null,
            'status' => ReservationStatus::Pending,
        ]);

        // ✨ ახალი ფუნქციონალი - რეზერვაციის შექმნისას Email-ების გაგზავნა
        // ვგზავნით "Pending" სტატუსის შეტყობინებას შექმნის დროს
        ReservationStatusChanged::dispatch($reservation, null, ReservationStatus::Pending);

        return $reservation;
    }
}

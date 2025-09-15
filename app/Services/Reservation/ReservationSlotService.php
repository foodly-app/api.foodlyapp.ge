<?php

namespace App\Services\Reservation;

use Carbon\Carbon;

class ReservationSlotService
{
    public function generateTimeSlots(string $startTime, string $endTime, int $intervalMinutes = 60, string $timezone = 'Asia/Tbilisi'): array
    {
        $slots = [];

        $start = Carbon::createFromFormat('H:i:s', $startTime, $timezone);
        $end = Carbon::createFromFormat('H:i:s', $endTime, $timezone);

        while ($start->lessThan($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($intervalMinutes);
        }

        return $slots;
    }
}

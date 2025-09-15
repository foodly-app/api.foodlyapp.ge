<?php

namespace App\Services\Reservation;

use App\Models\Restaurant\Restaurant;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class AvailabilityService
{
    public function generateAvailableSlots($model, string $reservationDate, string $dayOfWeek): array
    {
        // Convert day name to number (0=Sunday, 1=Monday, etc.)
        $dayNumber = $this->convertDayNameToNumber($dayOfWeek);
        
        $slotConfig = $model->reservationSlots()
            ->where('day_of_week', $dayNumber)
            ->where('available', true)
            ->first();

        if (!$slotConfig) {
            return [];
        }

        // Get timezone from restaurant model
        $timezone = $this->getModelTimezone($model);
        
        $start = Carbon::createFromFormat('H:i:s', $slotConfig->time_from, $timezone);
        $end = Carbon::createFromFormat('H:i:s', $slotConfig->time_to, $timezone);
        $interval = $slotConfig->slot_interval_minutes ?? 60; // Use configured interval or default to 60

        $slots = [];
        while ($start->lt($end)) {
            $time = $start->format('H:i');
            if ($this->isSlotAvailableForDate($model, $reservationDate, $dayNumber, $time)) {
                $slots[] = $time;
            }
            $start->addMinutes($interval);
        }

        return $slots;
    }

    public function isSlotAvailableForDate($model, string $reservationDate, int $dayOfWeek, string $time): bool
    {
        $slot = $model->reservationSlots()
            ->where('day_of_week', $dayOfWeek)
            ->where('time_from', '<=', $time)
            ->where('time_to', '>', $time)
            ->where('available', true)
            ->first();

        if (!$slot) {
            return false;
        }

        $existingReservation = Reservation::where('reservable_type', get_class($model))
            ->where('reservable_id', $model->id)
            ->where('reservation_date', $reservationDate)
            ->where('time_from', $time)
            ->exists();

        return !$existingReservation;
    }

    public function getReservationsForCalendar($model, string $startDate, string $endDate)
    {
        return Reservation::where('reservable_type', get_class($model))
            ->where('reservable_id', $model->id)
            ->whereBetween('reservation_date', [$startDate, $endDate])
            ->orderBy('reservation_date')
            ->orderBy('time_from')
            ->get(['reservation_date', 'time_from', 'time_to', 'guests_count']);
    }

    /**
     * Filter available slots by removing already reserved times
     */
    public function filterAvailableSlots(array $slots, $existingReservations): array
    {
        $reservedTimes = $existingReservations->pluck('time_from')->map(function ($time) {
            return Carbon::createFromFormat('H:i:s', $time)->format('H:i');
        })->toArray();

        return array_filter($slots, function ($slot) use ($reservedTimes) {
            return !in_array($slot, $reservedTimes);
        });
    }

    /**
     * Get timezone from model (Restaurant, Place, or Table)
     */
    private function getModelTimezone($model): string
    {
        // If the model is a Restaurant, get its timezone directly
        if ($model instanceof Restaurant) {
            return $model->time_zone ?? 'Asia/Tbilisi';
        }
        
        // If the model is a Place, get its restaurant's timezone
        if (method_exists($model, 'restaurant') && $model->restaurant) {
            return $model->restaurant->time_zone ?? 'Asia/Tbilisi';
        }
        
        // If the model is a Table, get its restaurant's timezone (through place if needed)
        if (method_exists($model, 'place') && $model->place && $model->place->restaurant) {
            return $model->place->restaurant->time_zone ?? 'Asia/Tbilisi';
        }
        
        // If the model has a restaurant_id and can load restaurant relation
        if (property_exists($model, 'restaurant_id') && $model->restaurant_id) {
            $restaurant = Restaurant::find($model->restaurant_id);
            if ($restaurant) {
                return $restaurant->time_zone ?? 'Asia/Tbilisi';
            }
        }
        
        // Default timezone if none found
        return 'Asia/Tbilisi';
    }

    /**
     * Convert day name to number (0=Sunday, 1=Monday, etc.)
     */
    private function convertDayNameToNumber(string $dayName): int
    {
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];

        return $days[$dayName] ?? 0;
    }
}

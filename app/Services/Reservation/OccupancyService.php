<?php

namespace App\Services\Reservation;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Table\Table;
use App\Models\Place\Place;
use App\Models\Restaurant\Restaurant;
use App\Models\Reservation\Reservation;

class OccupancyService
{
    public function getOccupancyByRestaurant(int $restaurantId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfWeek();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfWeek();

        $reservations = Reservation::where(function ($query) use ($restaurantId) {
            $query->where(function ($q) use ($restaurantId) {
                $q->where('type', 'restaurant')
                    ->where('reservable_type', Restaurant::class)
                    ->where('reservable_id', $restaurantId);
            })
                ->orWhere(function ($q) use ($restaurantId) {
                    $q->where('type', 'place')
                        ->whereHasMorph('reservable', [Place::class], function ($q2) use ($restaurantId) {
                            $q2->where('restaurant_id', $restaurantId);
                        });
                })
                ->orWhere(function ($q) use ($restaurantId) {
                    $q->where('type', 'table')
                        ->whereHasMorph('reservable', [Table::class], function ($q2) use ($restaurantId) {
                            $q2->whereHas('place', function ($q3) use ($restaurantId) {
                                $q3->where('restaurant_id', $restaurantId);
                            });
                        });
                });
        })
            ->whereBetween('reservation_date', [$start, $end])
            ->with(['reservable'])
            ->orderBy('reservation_date')
            ->orderBy('time_from')
            ->get();

        $occupancyData = $reservations->map(function ($reservation) {
            return [
                'date' => $reservation->reservation_date->toDateString(),
                'time_from' => $reservation->time_from,
                'time_to' => $reservation->time_to,
                'type' => $reservation->type,
                'reservable' => optional($reservation->reservable)->name ?? '',
            ];
        });

        return $occupancyData;
    }

    public function groupOccupancyByDate(Collection $occupancyData): Collection
    {
        return $occupancyData->groupBy('date');
    }

    public function groupByPeriod(Collection $occupancyData, string $period): Collection
    {
        switch ($period) {
            case 'day':
                return $occupancyData->groupBy('date');
            case 'week':
                return $occupancyData->groupBy(function ($item) {
                    return Carbon::parse($item['date'])->format('o-\WW'); // ISO week
                });
            case 'month':
                return $occupancyData->groupBy(function ($item) {
                    return Carbon::parse($item['date'])->format('Y-m');
                });
            case 'year':
                return $occupancyData->groupBy(function ($item) {
                    return Carbon::parse($item['date'])->format('Y');
                });
            default:
                return $occupancyData->groupBy('date'); // default fallback
        }
    }
}

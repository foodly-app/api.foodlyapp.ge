<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'reservation_date' => $this->reservation_date?->toDateString(),
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'guests_count' => $this->guests_count,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => is_object($this->status) ? $this->status->value : $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'reservable' => $this->whenLoaded('reservable', function () {
                $reservable = $this->reservable;
                if (!$reservable) return null;

                return [
                    'id' => $reservable->id,
                    'type' => class_basename($reservable),
                    'name' => $reservable->name ?? $reservable->slug ?? null,
                    'slug' => $reservable->slug ?? null,
                ];
            }),
        ];
    }
}

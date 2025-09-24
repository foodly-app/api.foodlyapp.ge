<?php

namespace App\Events;

use App\Models\Reservation\Reservation;
use App\Enums\ReservationStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChanged
{
    use Dispatchable, SerializesModels;

    public Reservation $reservation;
    public ?ReservationStatus $oldStatus;
    public ReservationStatus $newStatus;

    public function __construct(Reservation $reservation, ?ReservationStatus $oldStatus, ReservationStatus $newStatus)
    {
        $this->reservation = $reservation;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}

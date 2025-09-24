<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ReservationStatusChanged;
use App\Listeners\SendReservationCreatedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ReservationStatusChanged::class => [
            SendReservationCreatedNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}

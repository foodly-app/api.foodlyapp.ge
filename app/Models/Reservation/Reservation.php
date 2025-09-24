<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\ReservationStatus;
use App\Casts\ReservationStatusCast;
use Exception;

class Reservation extends Model
{
    protected $fillable = [
        'id',
        'type',
        'reservable_type',
        'reservable_id',
        'reservation_date',
        'time_from',
        'time_to',
        'guests_count',
        'occasion',
        'name',
        'phone',
        'email',
        'promo_code',
        'notes',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'guests_count' => 'integer',
        'status' => ReservationStatusCast::class,
    ];

    /**
     * BOG Transactions relationship
     */
    // public function bogTransactions(): HasMany
    // {
    //     return $this->hasMany(BOGTransaction::class);
    // }

    /**
     * Latest BOG Transaction relationship
     */
    // public function latestBOGTransaction(): HasOne
    // {
    //     return $this->hasOne(BOGTransaction::class)->latest();
    // }

    /**
     * Get the latest successful BOG transaction
     */
    // public function successfulBOGTransaction(): HasOne
    // {
    //     return $this->hasOne(BOGTransaction::class)
    //         ->where('status', 'completed')
    //         ->latest();
    // }

    /**
     * Check if reservation can initiate payment
     */
    public function canInitiatePayment(): bool
    {
        return $this->status === ReservationStatus::Confirmed;
    }

    /**
     * Check if reservation has successful payment
     */
    public function hasSuccessfulPayment(): bool
    {
        return $this->bogTransactions()->where('status', 'completed')->exists();
    }

    /**
     * Check if reservation has pending payment
     */
    public function hasPendingPayment(): bool
    {
        return $this->bogTransactions()
            ->whereIn('status', ['pending', 'processing'])
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Get payment amount for this reservation
     */
    public function getPaymentAmount(): float
    {
        // TODO: Implement your pricing logic based on:
        // - Restaurant pricing
        // - Number of guests  
        // - Time slot
        // - Special offers
        // - Promo codes

        // For now, return default amount
        return 50.00; // 50 GEL default
    }

    /**
     * Get guest email (compatibility with different field names)
     */
    public function getGuestEmailAttribute(): ?string
    {
        return $this->email;
    }

    /**
     * Get guest phone (compatibility with different field names)
     */
    public function getGuestPhoneAttribute(): ?string
    {
        return $this->phone;
    }

    /**
     * Get guest name
     */
    public function getGuestNameAttribute(): ?string
    {
        return $this->name;
    }

    /**
     * Get reservation datetime attribute for compatibility
     */
    public function getReservationDatetimeAttribute()
    {
        return $this->getReservationDateTime();
    }

    /**
     * Scope for reservations that need payment
     */
    public function scopeNeedsPayment($query)
    {
        return $query->where('status', ReservationStatus::Confirmed->value);
    }

    /**
     * Scope for paid reservations
     */
    public function scopePaid($query)
    {
        return $query->where('status', ReservationStatus::Paid->value);
    }

    /**
     * Scope for active reservations (not final state)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [
            ReservationStatus::Completed->value,
            ReservationStatus::Cancelled->value,
            ReservationStatus::NoShow->value
        ]);
    }



    public function reservable()
    {
        return $this->morphTo();
    }

    /**
     * Get the restaurant associated with this reservation
     */
    public function getRestaurant()
    {
        switch ($this->type) {
            case 'restaurant':
                return $this->reservable_type === 'App\\Models\\Restaurant'
                    ? $this->reservable
                    : null;

            case 'place':
                if ($this->reservable_type === 'App\\Models\\Place' && $this->reservable) {
                    return $this->reservable->restaurant ?? null;
                }
                return null;

            case 'table':
                if ($this->reservable_type === 'App\\Models\\Table' && $this->reservable && $this->reservable->place) {
                    return $this->reservable->place->restaurant ?? null;
                }
                return null;

            default:
                return null;
        }
    }

    /**
     * Get restaurant name for this reservation
     */
    public function getRestaurantName()
    {
        $restaurant = $this->getRestaurant();

        if (!$restaurant) {
            return 'N/A';
        }

        return $restaurant->translate(app()->getLocale())->name ??
            $restaurant->translate('ka')->name ??
            $restaurant->translate('en')->name ?? 'N/A';
    }

    /**
     * Get duration in minutes between time_from and time_to
     */
    public function getDurationInMinutes()
    {
        if (!$this->time_from || !$this->time_to) {
            return 0;
        }

        try {
            $timeFromParts = explode(':', $this->time_from);
            $timeToParts = explode(':', $this->time_to);

            if (count($timeFromParts) >= 2 && count($timeToParts) >= 2) {
                $fromHour = (int)$timeFromParts[0];
                $fromMinute = (int)$timeFromParts[1];
                $toHour = (int)$timeToParts[0];
                $toMinute = (int)$timeToParts[1];

                $startMinutes = ($fromHour * 60) + $fromMinute;
                $endMinutes = ($toHour * 60) + $toMinute;

                return max(0, $endMinutes - $startMinutes);
            }
        } catch (Exception $e) {
            return 0;
        }

        return 0;
    }

    /**
     * Get formatted duration string
     */
    public function getFormattedDuration()
    {
        $totalMinutes = $this->getDurationInMinutes();

        if ($totalMinutes <= 0) {
            return 'N/A';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        $duration = '';
        if ($hours > 0) {
            $duration .= $hours . ' საათი ';
        }
        if ($minutes > 0) {
            $duration .= $minutes . ' წუთი';
        }

        return trim($duration);
    }

    /**
     * Get reservation datetime
     */
    public function getReservationDateTime()
    {
        try {
            if ($this->time_from && $this->reservation_date) {
                $timeFromParts = explode(':', $this->time_from);
                if (count($timeFromParts) >= 2 && is_numeric($timeFromParts[0]) && is_numeric($timeFromParts[1])) {
                    $hour = (int)$timeFromParts[0];
                    $minute = (int)$timeFromParts[1];

                    if ($hour >= 0 && $hour <= 23 && $minute >= 0 && $minute <= 59) {
                        return $this->reservation_date->copy()
                            ->setHour($hour)
                            ->setMinute($minute)
                            ->setSecond(0);
                    }
                }
            }
        } catch (Exception $e) {
            // Fall back to date only
        }

        return $this->reservation_date;
    }
}

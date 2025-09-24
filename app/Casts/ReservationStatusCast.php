<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Enums\ReservationStatus;

class ReservationStatusCast implements CastsAttributes
{
    /**
     * Cast the given value when retrieving from database.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value instanceof ReservationStatus) {
            return $value;
        }

        // Normalize common legacy/capitalized values
        $normalized = match (true) {
            $value === null => null,
            is_string($value) => strtolower(str_replace(' ', '_', $value)),
            default => $value,
        };

        // Map some known variants to enum backing values
        $map = [
            'pending' => ReservationStatus::Pending,
            'confirmed' => ReservationStatus::Confirmed,
            'cancelled' => ReservationStatus::Cancelled,
            'paid' => ReservationStatus::Paid,
            'completed' => ReservationStatus::Completed,
            'no_show' => ReservationStatus::NoShow,
            'noshow' => ReservationStatus::NoShow,
            'no-show' => ReservationStatus::NoShow,
        ];

        return $map[$normalized] ?? (ReservationStatus::tryFrom($normalized) ?? null);
    }

    /**
     * Prepare the given value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof ReservationStatus) {
            return $value->value;
        }

        if (is_string($value)) {
            $normalized = strtolower(str_replace(' ', '_', $value));
            // If it's already a valid enum backing value
            if (ReservationStatus::tryFrom($normalized) !== null) {
                return $normalized;
            }
            // Try mapping known variants
            $map = [
                'pending' => 'pending',
                'confirmed' => 'confirmed',
                'cancelled' => 'cancelled',
                'paid' => 'paid',
                'completed' => 'completed',
                'no_show' => 'no_show',
                'noshow' => 'no_show',
                'no-show' => 'no_show',
            ];

            return $map[$normalized] ?? $value;
        }

        return $value;
    }
}

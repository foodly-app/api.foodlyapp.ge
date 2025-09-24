<?php

namespace App\Enums;

enum ReservationStatus: string
{
    // თქვენი არსებული ძირითადი status-ები (უცვლელი)
    case Pending = 'pending';       // ჯერ მხოლოდ მოთხოვნა გაგზავნილია
    case Confirmed = 'confirmed';   // რესტორანმა დაადასტურა
    case Cancelled = 'cancelled';   // რესტორანმა უარყო ან კლიენტმა გააუქმა
    case Paid = 'paid';             // მომხმარებელმა გადაიხადა (BOG Capture Success)
    
    // დამატებითი საბოლოო status-ები
    case Completed = 'completed';   // კლიენტი მოვიდა, რეზერვაცია წარმატებით დასრულდა
    case NoShow = 'no_show';        // გადახდილი, მაგრამ კლიენტი არ გამოჩნდა

    /**
     * BOG გადახდის სისტემასთან მუშაობისთვის helper methods
     */
    public function requiresPayment(): bool
    {
        return $this === self::Confirmed;
    }

    public function isPaymentCompleted(): bool
    {
        return in_array($this, [self::Paid, self::Completed]);
    }

    public function canBeRefunded(): bool
    {
        return in_array($this, [self::Paid, self::NoShow]);
    }

    public function isFinalState(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled, self::NoShow]);
    }

    public function isActive(): bool
    {
        return !$this->isFinalState();
    }

    /**
     * Status transition validation
     */
    public function canTransitionTo(self $newStatus): bool
    {
        return match([$this, $newStatus]) {
            // ძირითადი რეზერვაციის ნაკადი
            [self::Pending, self::Confirmed] => true,
            [self::Pending, self::Cancelled] => true,
            
            // გადახდის ნაკადი (მხოლოდ confirmed-დან)
            [self::Confirmed, self::Paid] => true,
            [self::Confirmed, self::Cancelled] => true,
            
            // რეზერვაციის დასასრული (paid-დან)
            [self::Paid, self::Completed] => true,    // კლიენტი მოვიდა
            [self::Paid, self::NoShow] => true,       // კლიენტი არ გამოჩნდა
            [self::Paid, self::Cancelled] => true,    // refund საჭიროა
            
            default => false
        };
    }

    /**
     * Get status label for UI
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Pending => 'მოლოდინში',
            self::Confirmed => 'დადასტურებული',
            self::Cancelled => 'გაუქმებული',
            self::Paid => 'გადახდილი',
            self::Completed => 'დასრულებული',
            self::NoShow => 'არ გამოჩენილა'
        };
    }

    /**
     * Get status color for UI (Tailwind classes)
     */
    public function getColorClass(): string
    {
        return match($this) {
            self::Pending => 'text-yellow-600 bg-yellow-100',
            self::Confirmed => 'text-blue-600 bg-blue-100',
            self::Cancelled => 'text-red-600 bg-red-100',
            self::Paid => 'text-green-600 bg-green-100',
            self::Completed => 'text-emerald-600 bg-emerald-100',
            self::NoShow => 'text-gray-600 bg-gray-100'
        };
    }

    /**
     * Get available actions for current status
     */
    public function getAvailableActions(): array
    {
        return match($this) {
            self::Pending => ['confirm', 'cancel'],
            self::Confirmed => ['pay', 'cancel'],
            self::Paid => ['complete', 'mark_no_show', 'refund'],
            self::Cancelled, self::Completed, self::NoShow => []
        };
    }
}

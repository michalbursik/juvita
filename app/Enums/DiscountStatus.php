<?php

namespace App\Enums;

enum DiscountStatus: string
{
    case ACTIVE = 'active';
    case APPLIED = 'applied';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktivní',
            self::APPLIED => 'Použito',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isApplied(): bool
    {
        return $this === self::APPLIED;
    }
}

<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrátor',
            self::EMPLOYEE => 'Zaměstnanec',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this === self::EMPLOYEE;
    }
}

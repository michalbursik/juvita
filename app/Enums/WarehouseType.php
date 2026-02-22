<?php

namespace App\Enums;

enum WarehouseType: string
{
    case MAIN = 'warehouse';
    case TEMPORARY = 'temporary_warehouse';
    case INTERNAL = 'internal_warehouse';
    case TRASH = 'trash_warehouse';

    public function label(): string
    {
        return match ($this) {
            self::MAIN => 'Hlavní sklad',
            self::TEMPORARY => 'Dočasný sklad',
            self::INTERNAL => 'Interní sklad',
            self::TRASH => 'Kompost/Odpad',
        };
    }

    public function isMain(): bool
    {
        return $this === self::MAIN;
    }

    public function isTemporary(): bool
    {
        return $this === self::TEMPORARY;
    }

    public function isInternal(): bool
    {
        return $this === self::INTERNAL;
    }

    public function isTrash(): bool
    {
        return $this === self::TRASH;
    }
}

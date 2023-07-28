<?php

namespace App\Enums;

enum WarehouseTypeEnum: string
{
    case MAIN = 'warehouse';
    case TEMPORARY = 'temporary_warehouse';
    case INTERNAL = 'internal_warehouse';
    case TRASH = 'trash_warehouse';
}

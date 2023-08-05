<?php

namespace App\Enums;

enum MovementTypeEnum: string
{
    case ISSUE = 'issue';
    case RECEIVE = 'receive';
    case MOVE = 'move';
    case CHECK = 'check';
}

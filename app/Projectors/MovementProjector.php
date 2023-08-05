<?php

namespace App\Projectors;

use App\Events\MovementCreated;
use App\Models\Movement;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MovementProjector extends Projector
{
    public function onMovementCreated(MovementCreated $event): void
    {
        (new Movement($event->movementAttributes))->writeable()->save();
    }
}

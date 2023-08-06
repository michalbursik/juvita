<?php

namespace App\Projectors;

use App\Events\MovementCreated;
use App\Models\Movement;
use App\Repositories\MovementRepository;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MovementProjector extends Projector
{
    public function __construct(
        private readonly MovementRepository $movementRepository
    ) {}

    public function onMovementCreated(MovementCreated $event): void
    {
        $this->movementRepository->store($event->movementAttributes);
    }
}

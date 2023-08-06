<?php

namespace App\Projectors;

use App\Events\CheckCreated;
use App\Repositories\CheckRepository;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CheckProjector extends Projector
{
    public function __construct(
        private readonly CheckRepository $checkRepository,
    ) {}

    public function onCheckCreated(CheckCreated $event)
    {
        $check = $this->checkRepository->store($event->checkAttributes);

        $this->checkRepository->createProductChecks($check, $event->checkAttributes);
    }
}

<?php

namespace App\Projectors;

use App\Events\UserCreated;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector implements ShouldQueue
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function onUserCreated(UserCreated $event): void
    {
        $this->userRepository->store($event->userAttributes);
    }
}

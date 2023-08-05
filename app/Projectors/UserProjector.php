<?php

namespace App\Projectors;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector implements ShouldQueue
{
    public function onUserCreated(UserCreated $event)
    {
        (new User($event->userAttributes))->writeable()->save();
    }
}

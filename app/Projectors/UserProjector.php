<?php

namespace App\Projectors;

use App\Events\UserCreated;
use App\Models\User;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        (new User($event->userAttributes))->writeable()->save();
    }
}

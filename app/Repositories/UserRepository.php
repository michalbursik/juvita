<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function store(array $attributes): User
    {
        $user = new User($attributes);
        $user->writeable()->save();

        return $user;
    }
}

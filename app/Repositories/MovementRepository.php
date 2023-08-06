<?php


namespace App\Repositories;


use App\Models\Movement;

class MovementRepository
{
    public function store(array $attributes): Movement
    {
        $movement = new Movement($attributes);
        $movement->writeable()->save();

        return $movement;
    }
}

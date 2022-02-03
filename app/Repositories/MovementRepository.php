<?php


namespace App\Repositories;


use App\Models\Movement;

class MovementRepository
{
    public function store(array $data): Movement
    {
        $movement = new Movement($data);

        $movement->save();

        return $movement;
    }

    public function destroy(Movement $movement)
    {
        $movement->delete();
    }
}

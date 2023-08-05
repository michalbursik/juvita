<?php


namespace App\Repositories;


use App\Models\Movement;

class MovementRepository
{
    public function store(array $data): Movement
    {
        return Movement::createWithAttributes($data);
    }

//    public function destroy(Movement $movement)
//    {
//        $movement->delete();
//    }
}

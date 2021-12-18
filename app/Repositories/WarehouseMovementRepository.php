<?php


namespace App\Repositories;


use App\Models\WarehouseMovement;

class WarehouseMovementRepository
{
    public function store(array $data): WarehouseMovement
    {
        $warehouseMovement = new WarehouseMovement($data);

        $warehouseMovement->save();

        return $warehouseMovement;
    }
}

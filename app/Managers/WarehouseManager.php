<?php


namespace App\Managers;


use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseMovement;

class WarehouseManager
{
    public function issue(WarehouseMovement $warehouseMovement)
    {
        $warehouseProduct = $warehouseMovement->warehouse->products()
            ->where('id', $warehouseMovement->product->id)
            ->first();

        $warehouseProduct->pivot->amount -= $warehouseMovement->amount;

        $warehouseProduct->pivot->save();
    }

    public function receipt(WarehouseMovement $warehouseMovement)
    {
        $warehouseProduct = $warehouseMovement->warehouse->products()
            ->where('id', $warehouseMovement->product->id)
            ->first();

        $warehouseProduct->pivot->amount += $warehouseMovement->amount;
        $warehouseProduct->pivot->price = $warehouseMovement->price;

        $warehouseProduct->pivot->save();
    }
}

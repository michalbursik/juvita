<?php


namespace App\Managers;


use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Movement;
use Illuminate\Support\Facades\Log;

class WarehouseManager
{
    public function issue(Movement $movement)
    {
        $warehouseProduct = $movement->issueWarehouse->products()
            ->where('id', $movement->product->id)
            ->first();

        $warehouseProduct->product_warehouse->amount -= $movement->amount;
        $warehouseProduct->product_warehouse->save();
    }

    public function receipt(Movement $movement)
    {
        $warehouseProduct = $movement->receiptWarehouse->products()
            ->where('id', $movement->product->id)
            ->first();

        $warehouseProduct->product_warehouse->amount += $movement->amount;
        $warehouseProduct->product_warehouse->price = $movement->price;

        $warehouseProduct->product_warehouse->save();
    }

    public function transmission(Movement $movement)
    {
        // Reduce on issue warehouse
        $this->issue($movement);

        // Add on receipt warehouse
        $this->receipt($movement);
    }
}

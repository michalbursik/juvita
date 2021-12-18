<?php


namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

class WarehouseManager
{
    private Warehouse $warehouse;

    /**
     * WarehouseManager constructor.
     */
    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function issue(Product $product, float $amount)
    {
        $warehouseProduct = $this->warehouse->products()
            ->where('id', $product->id)
            ->first();

        $warehouseProduct->pivot->amount -= $amount;

        $warehouseProduct->pivot->save();
    }

    public function receipt(Product $product, float $amount)
    {
        $warehouseProduct = $this->warehouse->products()
            ->where('id', $product->id)
            ->first();

        $warehouseProduct->pivot->amount += $amount;

        $warehouseProduct->pivot->save();
    }
}

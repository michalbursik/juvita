<?php


namespace App\Managers;


use App\Exceptions\InsufficientAmountException;
use App\Models\WarehouseProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Movement;
use Illuminate\Support\Facades\Log;

class WarehouseManager
{
    /**
     * @throws InsufficientAmountException
     */
    public function issue(Movement $movement, WarehouseProduct $priceLevel)
    {
        /** @var Product $warehouseProduct */
        $warehouseProduct = $movement->issueWarehouse->products()
            ->find($movement->product->id);

        /** @var WarehouseProduct $priceLevel */
        $priceLevel = $warehouseProduct->priceLevels()
            ->find($priceLevel->id);

        if ((float) $priceLevel->amount < (float) $movement->amount) {
            throw new InsufficientAmountException();
        }

        $warehouseProduct->product_warehouse->amount = round((float) $warehouseProduct->product_warehouse->amount - (float) $movement->amount, 1);
        $warehouseProduct->product_warehouse->save();

        return $warehouseProduct;
    }

    public function receipt(Movement $movement)
    {
        $warehouseProduct = $movement->receiptWarehouse->products()
            ->where('id', $movement->product->id)
            ->first();

        $warehouseProduct->product_warehouse->amount = round((float) $warehouseProduct->product_warehouse->amount + (float) $movement->amount, 1);
        $warehouseProduct->product_warehouse->price = $movement->price;

        $warehouseProduct->product_warehouse->save();

        return $warehouseProduct;
    }

    /**
     * @throws InsufficientAmountException
     */
    public function transmission(Movement $movement, WarehouseProduct $priceLevel)
    {
        // Reduce on issue warehouse
        $this->issue($movement, $priceLevel);

        // Add on receipt warehouse
        $this->receipt($movement);
    }
}

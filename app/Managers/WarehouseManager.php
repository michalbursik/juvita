<?php


namespace App\Managers;


use App\Exceptions\InsufficientAmountException;
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
    public function issue(Movement $movement)
    {
        /** @var Product $warehouseProduct */
        $warehouseProduct = $movement->issueWarehouse->products()
            ->where('id', $movement->product->id)
            ->first();


        $priceLevel = $warehouseProduct->priceLevels
            ->where('price', $movement->price)
            ->first();

        Log::debug('issue', [
            'wp' => $warehouseProduct,
            'pl' => $priceLevel,
        ]);

        if ($priceLevel->amount < $movement->amount) {
            throw new InsufficientAmountException();
        }

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

    /**
     * @throws InsufficientAmountException
     */
    public function transmission(Movement $movement)
    {
        // Reduce on issue warehouse
        $this->issue($movement);

        // Add on receipt warehouse
        $this->receipt($movement);
    }
}

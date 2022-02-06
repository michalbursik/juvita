<?php


namespace App\Managers;


use App\Exceptions\InsufficientAmountException;
use App\Models\PriceLevel;
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
    public function issue(Movement $movement, PriceLevel $priceLevel)
    {
        /** @var Product $warehouseProduct */
        $warehouseProduct = $movement->issueWarehouse->products()
            ->find($movement->product->id);

        /** @var PriceLevel $priceLevel */
        $priceLevel = $warehouseProduct->priceLevels()
            ->find($priceLevel->id);

        Log::debug('', [
            $priceLevel->id => (double) $priceLevel->amount,
            $movement->id => (double) $movement->amount,
        ]);

        if ((double) $priceLevel->amount < (double) $movement->amount) {
            throw new InsufficientAmountException();
        }

        $warehouseProduct->product_warehouse->amount -= (double) $movement->amount;
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
    public function transmission(Movement $movement, PriceLevel $priceLevel)
    {
        // Reduce on issue warehouse
        $this->issue($movement, $priceLevel);

        // Add on receipt warehouse
        $this->receipt($movement);
    }
}

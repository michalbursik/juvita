<?php

namespace App\Domain\Warehouse\Projections;

use App\Domain\Warehouse\Events\InventoryChecked;
use App\Domain\Warehouse\Events\StockIssued;
use App\Domain\Warehouse\Events\StockReceived;
use App\Domain\Warehouse\Events\StockTransferred;
use App\Models\Check;
use App\Models\Discount;
use App\Models\Movement;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WarehouseProjector extends Projector
{
    public function onStockReceived(StockReceived $event): void
    {
        DB::transaction(function () use ($event) {
            $movement = Movement::create([
                'type' => Movement::TYPE_RECEIPT,
                'amount' => $event->amount,
                'price' => $event->price,
                'product_id' => $event->productId,
                'receipt_warehouse_id' => $event->warehouseId,
                'user_id' => $event->userId,
            ]);

            // Update product_warehouse pivot
            $warehouse = Warehouse::find($event->warehouseId);
            $product = $warehouse->products()->find($event->productId);

            $product->product_warehouse->amount = round((float) $product->product_warehouse->amount + (float) $event->amount, 1);
            $product->product_warehouse->price = $event->price;
            $product->product_warehouse->save();

            // Update or create PriceLevel
            $validFrom = now()->toImmutable();
            $validTo = $this->getValidTo($validFrom);

            $priceLevel = PriceLevel::where('warehouse_id', $event->warehouseId)
                ->where('product_id', $event->productId)
                ->where('price', $event->price)
                ->first();

            if ($priceLevel) {
                $priceLevel->amount = round((float) $priceLevel->amount + (float) $event->amount, 1);
                $priceLevel->save();
            } else {
                PriceLevel::create([
                    'validFrom' => $validFrom,
                    'validTo' => $validTo,
                    'amount' => $event->amount,
                    'price' => $event->price,
                    'product_id' => $event->productId,
                    'warehouse_id' => $event->warehouseId,
                    'status' => PriceLevel::STATUS_ACTIVE,
                ]);
            }
        });
    }

    public function onStockIssued(StockIssued $event): void
    {
        DB::transaction(function () use ($event) {
            $priceLevel = PriceLevel::findOrFail($event->priceLevelId);

            Movement::create([
                'type' => Movement::TYPE_ISSUE,
                'amount' => $event->amount,
                'price' => $priceLevel->price,
                'product_id' => $event->productId,
                'issue_warehouse_id' => $event->warehouseId,
                'user_id' => $event->userId,
            ]);

            // Update product_warehouse pivot
            $warehouse = Warehouse::find($event->warehouseId);
            $product = $warehouse->products()->find($event->productId);
            $product->product_warehouse->amount = round((float) $product->product_warehouse->amount - (float) $event->amount, 1);
            $product->product_warehouse->save();

            // Update PriceLevel
            $priceLevel->amount = round((float) $priceLevel->amount - (float) $event->amount, 1);
            $priceLevel->save();

            if ($priceLevel->amount <= 0) {
                $priceLevel->delete();
            }
        });
    }

    public function onStockTransferred(StockTransferred $event): void
    {
        DB::transaction(function () use ($event) {
            $priceLevel = PriceLevel::findOrFail($event->priceLevelId);

            Movement::create([
                'type' => Movement::TYPE_TRANSMISSION,
                'amount' => $event->amount,
                'price' => $priceLevel->price,
                'product_id' => $event->productId,
                'issue_warehouse_id' => $event->fromWarehouseId,
                'receipt_warehouse_id' => $event->toWarehouseId,
                'user_id' => $event->userId,
            ]);

            // Issue from source
            $fromWarehouse = Warehouse::find($event->fromWarehouseId);
            $productFrom = $fromWarehouse->products()->find($event->productId);
            $productFrom->product_warehouse->amount = round((float) $productFrom->product_warehouse->amount - (float) $event->amount, 1);
            $productFrom->product_warehouse->save();

            $priceLevel->amount = round((float) $priceLevel->amount - (float) $event->amount, 1);
            $priceLevel->save();
            $priceLevelPrice = $priceLevel->price;
            if ($priceLevel->amount <= 0) {
                $priceLevel->delete();
            }

            // Receipt to destination
            $toWarehouse = Warehouse::find($event->toWarehouseId);
            $productTo = $toWarehouse->products()->find($event->productId);
            $productTo->product_warehouse->amount = round((float) $productTo->product_warehouse->amount + (float) $event->amount, 1);
            $productTo->product_warehouse->price = $priceLevelPrice;
            $productTo->product_warehouse->save();

            $validFrom = now()->toImmutable();
            $validTo = $this->getValidTo($validFrom);

            $destPriceLevel = PriceLevel::where('warehouse_id', $event->toWarehouseId)
                ->where('product_id', $event->productId)
                ->where('price', $priceLevelPrice)
                ->first();

            if ($destPriceLevel) {
                $destPriceLevel->amount = round((float) $destPriceLevel->amount + (float) $event->amount, 1);
                $destPriceLevel->save();
            } else {
                PriceLevel::create([
                    'validFrom' => $validFrom,
                    'validTo' => $validTo,
                    'amount' => $event->amount,
                    'price' => $priceLevelPrice,
                    'product_id' => $event->productId,
                    'warehouse_id' => $event->toWarehouseId,
                    'status' => PriceLevel::STATUS_ACTIVE,
                ]);
            }
        });
    }

    public function onInventoryChecked(InventoryChecked $event): void
    {
        DB::transaction(function () use ($event) {
            $check = Check::create([
                'warehouse_id' => $event->warehouseId,
                'user_id' => $event->userId,
                'discount' => $event->discount,
            ]);

            foreach ($event->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                $priceLevel = PriceLevel::findOrFail($productData['price_level_id']);

                $check->products()->save($product, [
                    'amount_before' => $productData['amount_before'],
                    'amount_after' => $productData['amount_after'],
                    'price_level_id' => $productData['price_level_id'],
                    'price' => $productData['price'],
                ]);

                // Create correction movement
                $diff = $productData['amount_before'] - $productData['amount_after'];
                $movement = Movement::create([
                    'type' => Movement::TYPE_CHECK,
                    'amount' => $diff,
                    'price' => $productData['price'],
                    'product_id' => $productData['product_id'],
                    'issue_warehouse_id' => $event->warehouseId,
                    'user_id' => $event->userId,
                ]);

                // Update warehouse stock
                $warehouse = Warehouse::find($event->warehouseId);
                $wp = $warehouse->products()->find($productData['product_id']);
                $wp->product_warehouse->amount = round((float) $wp->product_warehouse->amount - (float) $diff, 1);
                $wp->product_warehouse->save();

                // Update price level
                $priceLevel->amount = round((float) $priceLevel->amount - (float) $diff, 1);
                $priceLevel->save();

                if ($priceLevel->amount <= 0) {
                    $priceLevel->delete();
                }
            }

            // Cleanup discounts (already handled by service logic usually, but projector should reflect state)
            Discount::where('warehouse_id', $event->warehouseId)->delete();
        });
    }

    private function getValidTo(CarbonImmutable $validFrom): CarbonImmutable
    {
        if ($validFrom->isSunday()) {
            return $validFrom->nextWeekday()->endOfWeek();
        }
        return $validFrom->endOfWeek();
    }
}

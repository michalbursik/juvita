<?php

namespace App\Services;

use App\Domain\Warehouse\Aggregates\WarehouseAggregate;
use App\DTOs\CheckDTO;
use App\DTOs\MovementDTO;
use App\Enums\WarehouseType;
use App\Models\Discount;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;

class WarehouseService
{
    public function listWarehouses(bool $includeInactive = false): Collection
    {
        $query = Warehouse::query();

        if (! $includeInactive) {
            $query->where('active', true);
        }

        return $query->get();
    }

    public function createWarehouse(array $data): Warehouse
    {
        if (empty($data['type'])) {
            $data['type'] = WarehouseType::TEMPORARY;
        }

        $warehouse = Warehouse::create($data);

        $products = Product::all();
        $pivotAttributes = [];
        foreach ($products as $product) {
            $pivotAttributes[$product->id] = [
                'amount' => 0,
                'price' => 0.00,
            ];
        }

        $warehouse->products()->sync($pivotAttributes);

        return $warehouse;
    }

    public function updateWarehouse(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);

        return $warehouse;
    }

    public function deleteWarehouse(Warehouse $warehouse): void
    {
        $warehouse->products()->detach();
        $warehouse->delete();
    }

    public function receiveStock(MovementDTO $dto): void
    {
        WarehouseAggregate::retrieve($dto->receiptWarehouseId)
            ->receiveStock($dto->productId, $dto->receiptWarehouseId, $dto->amount, $dto->price, $dto->userId)
            ->persist();
    }

    public function issueStock(MovementDTO $dto): void
    {
        WarehouseAggregate::retrieve($dto->issueWarehouseId)
            ->issueStock($dto->productId, $dto->issueWarehouseId, $dto->priceLevelId, $dto->amount, $dto->userId)
            ->persist();
    }

    public function transferStock(MovementDTO $dto): void
    {
        WarehouseAggregate::retrieve($dto->issueWarehouseId)
            ->transferStock($dto->productId, $dto->issueWarehouseId, $dto->receiptWarehouseId, $dto->priceLevelId, $dto->amount, $dto->userId)
            ->persist();
    }

    public function checkInventory(CheckDTO $dto): void
    {
        $discounts = Discount::where('warehouse_id', $dto->warehouseId)->get();
        $discountAmount = $discounts->reduce(function ($carry, Discount $discount) {
            return $carry - (float) $discount->amount;
        }, 0.00);

        // Prepare products data with amount_before and price from the current state
        $preparedProducts = [];
        foreach ($dto->products as $productData) {
            $priceLevel = PriceLevel::findOrFail($productData['price_level_id']);
            $preparedProducts[] = [
                'product_id' => $productData['product_id'],
                'price_level_id' => $productData['price_level_id'],
                'amount_before' => (float) $priceLevel->amount,
                'amount_after' => (float) $productData['amount'],
                'price' => (float) $priceLevel->price,
            ];
        }

        WarehouseAggregate::retrieve($dto->warehouseId)
            ->checkInventory($dto->warehouseId, $dto->userId, $discountAmount, $preparedProducts)
            ->persist();
    }
}

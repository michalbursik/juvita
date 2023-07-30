<?php

namespace App\Services;

use App\Exceptions\RebuildMovementsException;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\Movement;
use App\Models\WarehouseProduct;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\PriceLevelRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RebuildMovementsService
{
    private Carbon $from;
    private Carbon $to;
    private Warehouse $warehouseFrom;
    private Warehouse $warehouseTo;
    private Product $product;
    private float $initialAmount = 0.0;
    private float $initialPrice = 0.0;

    public function __construct(Warehouse $warehouseFrom, Warehouse $warehouseTo, Product $product)
    {
        $this->warehouseFrom = $warehouseFrom;
        $this->warehouseTo = $warehouseTo;
        $this->product = $product;

        $this->from = now()->subDay();
        $this->to = now();
    }

    public function rebuild()
    {
        $movements = $this->getMovements();
        $priceLevel = $this->initializeProduct();
        $movements = $this->moveMovements($movements);

        $this->applyMovements($movements, $priceLevel);
    }

    private function getMovements(): Collection
    {
        return $this->warehouseFrom
            ->movements()
            ->where('product_id', $this->product->id)
            ->whereBetween('created_at', [$this->from, $this->to])
            ->get();
    }


    private function initializeProduct(): WarehouseProduct
    {
        $priceLevelRepository = new PriceLevelRepository();

        $validFrom = now()->toImmutable();
        $validTo = now()->toImmutable()->addWeek()->endOfWeek();

        $priceLevel = $priceLevelRepository->updateOrCreate([
            'warehouse_id' => $this->warehouseTo->id,
            'product_id' => $this->product->id,
            'price' => $this->initialPrice,
            'amount' => $this->initialAmount,
            'validFrom' => $validFrom,
            'validTo' => $validTo,
        ]);

        $this->warehouseTo->products()->save(
            $this->product, [
                'amount' => $this->initialAmount,
                'price' => $this->initialPrice,
            ]
        );

        return $priceLevel;
    }

    private function moveMovements(Collection $movements): Collection
    {
        return $movements->map(function (Movement $movement) {
            if ($movement->issue_warehouse_id === $this->warehouseFrom->id) {
                $movement->issue_warehouse_id = $this->warehouseTo->id;
            }

            if ($movement->receipt_warehouse_id === $this->warehouseFrom->id) {
                $movement->receipt_warehouse_id = $this->warehouseTo->id;
            }

            return $movement;
        });
    }

    private function applyMovements(Collection $movements, WarehouseProduct $priceLevel)
    {
        $warehouseManager = new WarehouseManager();
        $pricesManager = new PricesManager();

        $movements->each(function (Movement $movement) use ($warehouseManager, $pricesManager, $priceLevel) {
            if ($movement->type === Movement::TYPE_ISSUE) {
                $warehouseManager->issue($movement, $priceLevel);
                $pricesManager->issue($movement, $priceLevel);
            } else if ($movement->type === Movement::TYPE_RECEIPT) {
                $warehouseManager->receipt($movement);
                $pricesManager->receipt($movement);
            } else if ($movement->type === Movement::TYPE_TRANSMISSION) {
                $warehouseManager->transmission($movement, $priceLevel);
                $pricesManager->transmission($movement, $priceLevel);
            } else {
                throw new RebuildMovementsException('Unable to process movement ' . $movement->id);
            }
        });
    }

    /**
     * @return Carbon
     */
    public function getFrom(): Carbon
    {
        return $this->from;
    }

    /**
     * @param Carbon $from
     */
    public function setFrom(Carbon $from): void
    {
        $this->from = $from;
    }

    /**
     * @return Carbon
     */
    public function getTo(): Carbon
    {
        return $this->to;
    }

    /**
     * @param Carbon $to
     */
    public function setTo(Carbon $to): void
    {
        $this->to = $to;
    }

    /**
     * @return Warehouse
     */
    public function getWarehouseFrom(): Warehouse
    {
        return $this->warehouseFrom;
    }

    /**
     * @param Warehouse $warehouseFrom
     */
    public function setWarehouseFrom(Warehouse $warehouseFrom): void
    {
        $this->warehouseFrom = $warehouseFrom;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return float
     */
    public function getInitialAmount(): float
    {
        return $this->initialAmount;
    }

    /**
     * @param float $initialAmount
     * @return RebuildMovementsService
     */
    public function setInitialAmount(float $initialAmount): RebuildMovementsService
    {
        $this->initialAmount = $initialAmount;

        return $this;
    }

    /**
     * @return Warehouse
     */
    public function getWarehouseTo(): Warehouse
    {
        return $this->warehouseTo;
    }

    /**
     * @param Warehouse $warehouseTo
     */
    public function setWarehouseTo(Warehouse $warehouseTo): void
    {
        $this->warehouseTo = $warehouseTo;
    }

    /**
     * @return float
     */
    public function getInitialPrice(): float
    {
        return $this->initialPrice;
    }

    /**
     * @param float $initialPrice
     * @return RebuildMovementsService
     */
    public function setInitialPrice(float $initialPrice): RebuildMovementsService
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }
}

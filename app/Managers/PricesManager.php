<?php


namespace App\Managers;


use App\Models\ProductWarehouse;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Movement;
use App\Repositories\PriceLevelRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class PricesManager
{
    private PriceLevelRepository $priceLevelRepository;

    /**
     * WarehouseManager constructor.
     */
    public function __construct()
    {
        $this->priceLevelRepository = new PriceLevelRepository();
    }

    public function issue(Movement $movement, ProductWarehouse $priceLevel): ProductWarehouse
    {
        $product = $this->priceLevelRepository->update($priceLevel, $movement->amount * -1);

        if ($product->amount <= 0) {
            $this->priceLevelRepository->delete($priceLevel);
        }

        return $priceLevel;
    }

    public function receipt(Movement $movement): ProductWarehouse
    {
        $validFrom = now()->toImmutable();
        $validTo = $this->getValidTo($validFrom);

        return $this->priceLevelRepository->updateOrCreate([
            'validFrom' => $validFrom,
            'validTo' => $validTo,
            'amount' => $movement->amount,
            'price' => $movement->price,
            'product_id' => $movement->product->id,
            'warehouse_id' => $movement->receiptWarehouse->id,
        ]);
    }

    /**
     * @param CarbonImmutable $validFrom
     * @return CarbonImmutable
     */
    public function getValidTo(CarbonImmutable $validFrom): CarbonImmutable
    {
        if ($validFrom->isSunday()) {
            $validTo = $validFrom->nextWeekday()->endOfWeek();
        } else {
            $validTo = $validFrom->endOfWeek();
        }
        return $validTo;
    }

    public function transmission(Movement $movement, ProductWarehouse $priceLevel)
    {
        // Issue from issue warehouse
        $this->issue($movement, $priceLevel);

        // Receipt on receipt warehouse
        $this->receipt($movement);
    }
}

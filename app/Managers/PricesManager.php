<?php


namespace App\Managers;


use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseMovement;
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

    public function issue(WarehouseMovement $warehouseMovement, PriceLevel $priceLevel): PriceLevel
    {
        $product = $this->priceLevelRepository->update($priceLevel, $warehouseMovement->amount * -1);

        if ($product->amount <= 0) {
            $this->priceLevelRepository->delete($priceLevel);
        }

        return $priceLevel;
    }

    public function receipt(WarehouseMovement $warehouseMovement): PriceLevel
    {
        $validFrom = now()->toImmutable();
        $validTo = $this->getValidTo($validFrom);

        return $this->priceLevelRepository->updateOrCreate([
            'validFrom' => $validFrom,
            'validTo' => $validTo,
            'amount' => $warehouseMovement->amount,
            'price' => $warehouseMovement->price,
            'product_id' => $warehouseMovement->product->id,
            'warehouse_id' => $warehouseMovement->warehouse->id,
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
}

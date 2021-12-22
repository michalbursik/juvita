<?php


namespace App\Managers;


use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\PriceLevelRepository;
use Carbon\Carbon;

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

    public function issue(float $price)
    {

    }

    public function receipt(Product $product, float $price)
    {
        $validFrom = now()->toImmutable();

        if ($validFrom->isSunday()) {
            $validTo = $validFrom->nextWeekday()->endOfWeek();
        } else {
            $validTo = $validFrom->endOfWeek();
        }

        return $this->priceLevelRepository->getOrStore([
            'validFrom' => $validFrom,
            'validTo' => $validTo,
            'price' => $price,
            'product_id' => $product->id,
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\Discount;

class DiscountService
{
    public function getTotalDiscount(string $warehouse_uuid)
    {
        $discounts = Discount::query()->where('warehouse_uuid', $warehouse_uuid)->get();

        $totalDiscount = $discounts->reduce(function ($carry, Discount $discount) {
            return $carry - $discount->amount;
        }, 0.00);

        foreach ($discounts as $discount) {
            $discount->remove();
        }


        return $totalDiscount;
    }
}

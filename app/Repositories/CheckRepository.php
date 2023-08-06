<?php

namespace App\Repositories;

use App\Models\Check;
use App\Models\ProductCheck;
use App\Models\Price;
use Illuminate\Support\Collection;

class CheckRepository
{
    public function store(array $validatedData): Check
    {
        $check = new Check($validatedData);

        $check->writeable()->save();

        return $check;
    }

    public function createProductChecks(Check $check, array $validatedData): Collection
    {
        $productChecks = collect();
        $checkTotalPrice = 0.00;

        foreach ($validatedData['products'] as $productData) {
            $price = Price::uuid($productData['price_uuid']);

            $productCheck = ProductCheck::createWithAttributes([
                'check_uuid' => $check->uuid,
                'amount_before' => $price->amount,
                'amount_after' => $productData['amount'],
                'price' => $price->price,
                'total_price' => $price->price * $productData['amount'],
                'warehouse_product_uuid' => $productData['warehouse_product_uuid'],
                'price_uuid' => $productData['price_uuid'],
                'user_uuid' => $validatedData['user_uuid']
            ]);

            $productChecks->push($productCheck);
            $checkTotalPrice += $productCheck->total_price;
        }

        $check->total_price = $checkTotalPrice;
        $check->writeable()->update();

        return $productChecks;
    }
}

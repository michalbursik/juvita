<?php

namespace App\Repositories;

use App\Models\Price;

class WarehouseProductPriceRepository
{
    public function store(array $attributes): Price
    {
        $user = new Price($attributes);
        $user->writeable()->save();

        return $user;
    }

}

<?php

namespace App\Repositories;

use App\Models\ProductCheck;

class ProductCheckRepository
{
    public function store(array $attributes): ProductCheck
    {
        $productCheck = new ProductCheck($attributes);
        $productCheck->writeable()->save();

        return $productCheck;
    }
}

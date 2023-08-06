<?php


namespace App\Repositories;


use App\Models\Product;

class ProductRepository
{
    public function store(array $attributes): Product
    {
        $product = new Product($attributes);
        $product->writeable()->save();

        return $product;
    }

    public function update(Product $product, array $attributes): Product
    {
        $product->writeable()->update($attributes);

        return $product->fresh();
    }
}

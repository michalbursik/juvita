<?php


namespace App\Repositories;


use App\Models\Product;

class ProductRepository
{
    public function store(array $data): Product
    {
        $product = new Product($data);

        $product->save();

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }
}

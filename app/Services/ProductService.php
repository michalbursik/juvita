<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Models\Warehouse;

class ProductService
{
    public function createProduct(ProductDTO $dto): Product
    {
        $product = Product::create($dto->toArray());

        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $warehouse->products()->save($product, [
                'amount' => 0,
                'price' => 0.00,
            ]);
        }

        return $product;
    }

    public function updateProduct(Product $product, ProductDTO $dto): Product
    {
        $product->update($dto->toArray());

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }
}

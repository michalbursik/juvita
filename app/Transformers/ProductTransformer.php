<?php

namespace App\Transformers;

use App\Models\Product;
use Flugg\Responder\Transformers\Transformer;

class ProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'priceLevels' => PriceLevelTransformer::class,
        'warehouseMovements' => WarehouseMovementTransformer::class,
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product): array
    {
        $data = [
            'id' => (int)$product->id,
            'name' => $product->name,
            'origin' => $product->origin,
            'active' => $product->active,
            'order' => $product->order,
            'unit' => $product->unit,
            'image' => $product->image,
        ];

        if ($product->pivot) {
            $data['amount'] = $product->pivot->amount;
            $data['price'] = $product->pivot->price;
        }

        return $data;
    }
}

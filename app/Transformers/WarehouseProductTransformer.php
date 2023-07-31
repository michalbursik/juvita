<?php

namespace App\Transformers;

use App\Models\WarehouseProduct;
use Flugg\Responder\Transformers\Transformer;

class WarehouseProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'product' => ProductTransformer::class
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
     * @param  WarehouseProduct $warehouseProduct
     * @return array
     */
    public function transform(WarehouseProduct $warehouseProduct): array
    {
        return [
            'id' => $warehouseProduct->id,
            'total_amount' => $warehouseProduct->total_amount,
            'name' => $warehouseProduct->product->name,
            'unit' => $warehouseProduct->product->unit,
            'image' => $warehouseProduct->product->image
        ];
    }
}

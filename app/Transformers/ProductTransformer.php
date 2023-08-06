<?php

namespace App\Transformers;

use App\Models\Product;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Log;

class ProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'movements' => MovementTransformer::class,
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
        return [
            'id' => $product->id,
            'uuid' => $product->uuid,
            'name' => $product->name,
            'origin' => $product->origin,
            'active' => $product->active,
            'order' => $product->order,
            'unit' => $product->unit,
            'image' => $product->image,
        ];
    }
}

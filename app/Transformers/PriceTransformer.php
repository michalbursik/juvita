<?php

namespace App\Transformers;

use App\Models\Price;
use Flugg\Responder\Transformers\Transformer;

class PriceTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  Price $price
     * @return array
     */
    public function transform(Price $price): array
    {
        return [
            'id' => $price->id,
            'uuid' => $price->uuid,
            'price' => $price->price,
            'amount' => $price->amount,
            'warehouse_product_uuid' => $price->warehouse_product_uuid,
        ];
    }
}

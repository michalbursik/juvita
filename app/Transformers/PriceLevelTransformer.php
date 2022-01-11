<?php

namespace App\Transformers;

use App\Models\PriceLevel;
use Flugg\Responder\Transformers\Transformer;

class PriceLevelTransformer extends Transformer
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
     * @param  PriceLevel $priceLevel
     * @return array
     */
    public function transform(PriceLevel $priceLevel): array
    {
        return [
            'id' => (int) $priceLevel->id,
            'amount' => $priceLevel->amount,
            'price' => $priceLevel->price,
            'validFrom' => $priceLevel->validFrom,
            'validTo' => $priceLevel->validTo,
            'product_id' => $priceLevel->product_id,
            'warehouse_id' => $priceLevel->warehouse_id,
        ];
    }
}

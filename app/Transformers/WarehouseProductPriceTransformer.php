<?php

namespace App\Transformers;

use App\Models\WarehouseProductPrice;
use Flugg\Responder\Transformers\Transformer;

class WarehouseProductPriceTransformer extends Transformer
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
     * @param  WarehouseProductPrice $warehouseProductPrice
     * @return array
     */
    public function transform(WarehouseProductPrice $warehouseProductPrice)
    {
        return [
            'id' => $warehouseProductPrice->id,
            'uuid' => $warehouseProductPrice->uuid,
            'price' => $warehouseProductPrice->price,
            'amount' => $warehouseProductPrice->amount,
            'warehouse_product_uuid' => $warehouseProductPrice->warehouse_product_uuid,
        ];
    }
}

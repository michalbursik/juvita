<?php

namespace App\Transformers;

use App\Models\ProductCheck;
use App\Models\WarehouseProduct;
use Flugg\Responder\Transformers\Transformer;

class ProductCheckTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'product' => WarehouseProductTransformer::class,
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
     * @param  ProductCheck $productCheck
     * @return array
     */
    public function transform(ProductCheck $productCheck): array
    {
        return [
            'id' => $productCheck->id,
            'uuid' => $productCheck->uuid,
            'amount_before' => $productCheck->amount_before,
            'amount_after' => $productCheck->amount_after,
            'price' => $productCheck->price,
            'total_price' => $productCheck->total_price,
            'check_uuid' => $productCheck->check_uuid,
            'warehouse_product_uuid' => $productCheck->warehouse_product_uuid,
            'price_uuid' => $productCheck->price_uuid,
        ];
    }
}

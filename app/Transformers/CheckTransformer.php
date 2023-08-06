<?php

namespace App\Transformers;

use App\Models\Check;
use App\Models\WarehouseProduct;
use Flugg\Responder\Transformers\Transformer;

class CheckTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [

    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'productChecks' => ProductCheckTransformer::class,
        'warehouse' => WarehouseTransformer::class,
        'user' => UserTransformer::class,
    ];

    /**
     * Transform the model.
     *
     * @param  Check $check
     * @return array
     */
    public function transform(Check $check): array
    {
        return [
            'id' => $check->id,
            'uuid' => $check->uuid,
            'discount' => $check->discount,
            'created_at' => $check->created_at->format('d. m. Y H:i:s'),
            'warehouse_uuid' => $check->warehouse_uuid,
        ];
    }
}

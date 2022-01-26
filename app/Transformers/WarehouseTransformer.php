<?php

namespace App\Transformers;

use App\Models\Warehouse;
use Flugg\Responder\Transformers\Transformer;

class WarehouseTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'products' => ProductTransformer::class,
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
     * @param Warehouse $warehouse
     * @return array
     */
    public function transform(Warehouse $warehouse): array
    {
        return [
            'id' => (int) $warehouse->id,
            'name' => $warehouse->name,
            'type' => $warehouse->type,
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\Movement;
use Flugg\Responder\Transformers\Transformer;

class MovementTransformer extends Transformer
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
        'product' => ProductTransformer::class,
        'sourceWarehouse' => WarehouseTransformer::class,
        'targetWarehouse' => WarehouseTransformer::class,
        'user' => UserTransformer::class,
    ];

    /**
     * Transform the model.
     *
     * @param  Movement $movement
     * @return array
     */
    public function transform(Movement $movement): array
    {
        return [
            'id' => $movement->id,
            'uuid' => $movement->uuid,
            'type' => $movement->type,
            'translated_type' => __('global.' . $movement->type->value),
            'amount' => $movement->amount,
            'price' => $movement->price,
            'created_at' => $movement->created_at,
            'created_at_utc' => $movement->getAttributes()['created_at'],
        ];
    }
}

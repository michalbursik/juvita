<?php

namespace App\Transformers;

use App\Models\WarehouseMovement;
use Flugg\Responder\Transformers\Transformer;

class WarehouseMovementTransformer extends Transformer
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
        'warehouse' => WarehouseTransformer::class,
        'user' => UserTransformer::class,
    ];

    /**
     * Transform the model.
     *
     * @param  WarehouseMovement $warehouseMovement
     * @return array
     */
    public function transform(WarehouseMovement $warehouseMovement): array
    {
        return [
            'id' => (int) $warehouseMovement->id,
            'type' => $warehouseMovement->type,
            'translated_type' => __('global.' . $warehouseMovement->type),
            'amount' => $warehouseMovement->amount,
            'price' => $warehouseMovement->price,
            'product_id' => $warehouseMovement->product_id,
            'warehouse_id' => $warehouseMovement->warehouse_id,
            'user_id' => $warehouseMovement->user_id,
            'created_at' => $warehouseMovement->created_at,
        ];
    }
}

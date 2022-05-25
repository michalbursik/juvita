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
        'issueWarehouse' => WarehouseTransformer::class,
        'receiptWarehouse' => WarehouseTransformer::class,
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
            'id' => (int) $movement->id,
            'type' => $movement->type,
            'translated_type' => __('global.' . $movement->type),
            'amount' => $movement->amount,
            'price' => $movement->price,
            // Loaded with relation
//            'product_id' => $movement->product_id,
//            'issue_warehouse_id' => $movement->issue_warehouse_id,
//            'receipt_warehouse_id' => $movement->receipt_warehouse_id,
//            'user_id' => $movement->user_id,
            'created_at' => $movement->created_at,
            'created_at_utc' => $movement->getAttributes()['created_at'],
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\Discount;
use Flugg\Responder\Transformers\Transformer;

class DiscountTransformer extends Transformer
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
    protected $load = [
        'warehouse' => WarehouseTransformer::class,
        'user' => UserTransformer::class,
    ];

    /**
     * Transform the model.
     *
     * @param Discount $discount
     * @return array
     */
    public function transform(Discount $discount): array
    {
        return [
            'id' => (int) $discount->id,
            'amount' => $discount->amount,
        ];
    }
}

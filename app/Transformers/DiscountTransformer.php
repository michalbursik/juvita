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
            'id' => $discount->id,
            'uuid' => $discount->uuid,
            'amount' => $discount->amount,
            'note' => $discount->note,
            'created_at' => $discount->created_at->format('d. m. Y H:i:s'),
            'updated_at' => $discount->updated_at->format('d. m. Y H:i:s'),
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\Check;
use Flugg\Responder\Transformers\Transformer;

class CheckTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'user' => UserTransformer::class,
        'warehouse' => WarehouseTransformer::class
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'products' => ProductTransformer::class
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
            'id' => (int) $check->id,
        ];
    }
}

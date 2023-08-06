<?php

namespace App\Models;

use App\Traits\Eventable;
use App\Transformers\PriceTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\Price
 *
 * @property int $id
 * @property string $uuid
 * @property string $warehouse_product_uuid
 * @property float $amount
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\WarehouseProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereWarehouseProductUuid($value)
 * @mixin \Eloquent
 */
class Price extends Projection implements Transformable
{
    use HasFactory, Eventable;

    public $fillable = [
        'price',
        'amount',
        'warehouse_product_uuid'
    ];

    protected $casts = [
        'amount' => 'float',
        'price' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(WarehouseProduct::class, 'warehouse_product_uuid');
    }

    public function transformer(): string
    {
        return PriceTransformer::class;
    }
}

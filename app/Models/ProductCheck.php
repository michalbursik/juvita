<?php

namespace App\Models;

use App\Traits\Eventable;
use App\Transformers\ProductCheckTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\ProductCheck
 *
 * @property int $id
 * @property string $uuid
 * @property float $amount_before
 * @property float $amount_after
 * @property \App\Models\Price $price
 * @property float|null $total_price
 * @property string $check_uuid
 * @property string $warehouse_product_uuid
 * @property string $price_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Check $check
 * @property-read \App\Models\WarehouseProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereAmountAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereAmountBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereCheckUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck wherePriceUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCheck whereWarehouseProductUuid($value)
 * @mixin \Eloquent
 */
class ProductCheck extends Projection implements Transformable
{
    use HasFactory, Eventable;

    public $fillable = [
        'amount_before',
        'amount_after',
        'price',
        'total_price',
        'check_uuid',
        'warehouse_product_uuid',
        'price_uuid'
    ];

    public function check(): BelongsTo
    {
        return $this->belongsTo(Check::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(WarehouseProduct::class, 'warehouse_product_uuid');
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }

    public function transformer(): string
    {
        return ProductCheckTransformer::class;
    }
}

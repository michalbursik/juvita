<?php

namespace App\Models;

use App\Events\WarehouseProductPriceCreated;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WarehouseProductPrice
 *
 * @property int $id
 * @property string $uuid
 * @property string $warehouse_product_uuid
 * @property float $amount
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\WarehouseProduct $warehouseProduct
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseProductPrice whereWarehouseProductUuid($value)
 * @mixin \Eloquent
 */
class WarehouseProductPrice extends ModelProjection implements Transformable
{
    use HasFactory;

    public $fillable = [
        'price',
        'amount',
        'warehouse_product_uuid'
    ];

    protected $casts = [
        'amount' => 'float',
        'price' => 'float',
    ];

    public static function setModelEvents(): void
    {
         static::setCreateEvent(WarehouseProductPriceCreated::class);
    }

    public function warehouseProduct(): BelongsTo
    {
        return $this->belongsTo(WarehouseProduct::class);
    }

    public function transformer()
    {
        // return WarehouseProductPriceTransformer::class;
    }
}

<?php

namespace App\Models;

use App\Traits\Eventable;
use App\Transformers\WarehouseProductTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\WarehouseProduct
 *
 * @property int $id
 * @property string $uuid
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @property float $total_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Price> $prices
 * @property-read int|null $prices_count
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse $warehouse
 * @method static Builder|WarehouseProduct exact(\App\Models\Warehouse|string $warehouse, \App\Models\Product|string $product)
 * @method static Builder|WarehouseProduct newModelQuery()
 * @method static Builder|WarehouseProduct newQuery()
 * @method static Builder|WarehouseProduct onlyTrashed()
 * @method static Builder|WarehouseProduct query()
 * @method static Builder|WarehouseProduct whereCreatedAt($value)
 * @method static Builder|WarehouseProduct whereDeletedAt($value)
 * @method static Builder|WarehouseProduct whereId($value)
 * @method static Builder|WarehouseProduct whereProductUuid($value)
 * @method static Builder|WarehouseProduct whereTotalAmount($value)
 * @method static Builder|WarehouseProduct whereUpdatedAt($value)
 * @method static Builder|WarehouseProduct whereUuid($value)
 * @method static Builder|WarehouseProduct whereWarehouseUuid($value)
 * @method static Builder|WarehouseProduct withTrashed()
 * @method static Builder|WarehouseProduct withoutTrashed()
 * @mixin \Eloquent
 */
class WarehouseProduct extends Projection implements Transformable
{
    use HasFactory, SoftDeletes, Eventable;

    // Clear activation ??
    const STATUS_ACTIVE = 'active';

    // TODO - whe issue all amounts => REMOVE warehouseProductPrice
    // Warehouse receipt product with price.
    const STATUS_DISABLED = 'disabled';
    // All amount of product was issued away from warehouse.
    const STATUS_REMOVED = 'removed';
    // When check issues all amount of product from any warehouse.

    protected $fillable = [
        'warehouse_uuid',
        'product_uuid',
        'total_amount',
        'order',

//        'validFrom',
//        'validTo',
//        'status',
    ];

    protected $with = [
        'prices'
    ];

    protected $casts = [
        'total_amount' => 'float',
    ];

    public function scopeExact(Builder $query, Warehouse|string $warehouse, Product|string $product)
    {
        $warehouseUuid = $warehouse instanceof Warehouse ? $warehouse->uuid : $warehouse;
        $productUuid = $product instanceof Product ? $product->uuid : $product;

        $query->where('product_uuid', $productUuid)
            ->where('warehouse_uuid', $warehouseUuid);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function transformer(): string
    {
        return WarehouseProductTransformer::class;
    }
}

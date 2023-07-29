<?php

namespace App\Models;

use App\Events\ProductWarehouseCreated;
use App\Interfaces\Eventable;
use App\Traits\UuidHelpers;
use App\Transformers\PriceLevelTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\PriceLevel
 *
 * @property int $id
 * @property string $amount
 * @property string $price
 * @property string $validFrom
 * @property string $validTo
 * @property string $status
 * @property int $product_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\PriceLevelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereWarehouseId($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Query\Builder|ProductWarehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductWarehouse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductWarehouse withoutTrashed()
 * @property-read \App\Models\Warehouse $warehouse
 * @property string $uuid
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductWarehouse from(\App\Models\Warehouse|string $warehouse, \App\Models\Product|string $product, float $price)
 * @method static Builder|ProductWarehouse karamel(\App\Models\Warehouse|string $warehouse, \App\Models\Product|string $product, float $price)
 * @method static Builder|ProductWarehouse exact(\App\Models\Warehouse|string $warehouse, \App\Models\Product|string $product, float $price)
 * @mixin \Eloquent
 */
class ProductWarehouse extends Projection implements Transformable, Eventable
{
    use HasFactory, SoftDeletes, UuidHelpers;

    const STATUS_ACTIVE = 'active';

    // Warehouse receipt product with price.
    const STATUS_DISABLED = 'disabled';
    // All amount of product was issued away from warehouse.
    const STATUS_REMOVED = 'removed';
    // When check issues all amount of product from any warehouse.
    protected $table = 'product_warehouse';
    protected $fillable = [
        'warehouse_uuid',
        'product_uuid',
        'amount',
        'price',
        'validFrom',
        'validTo',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'price' => 'float',
    ];

    public static function setModelEvents(): void
    {
        static::setCreateEvent(ProductWarehouseCreated::class);
    }

    public function scopeExact(Builder $query, Warehouse|string $warehouse, Product|string $product, float $price)
    {
        $warehouseUuid = $warehouse instanceof Warehouse ? $warehouse->uuid : $warehouse;
        $productUuid = $product instanceof Product ? $product->uuid : $product;

        $query->where('product_uuid', $productUuid)
            ->where('warehouse_uuid', $warehouseUuid)
            ->where('price', $price);
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
        return PriceLevelTransformer::class;
    }
}

<?php

namespace App\Models;

use App\Transformers\PriceLevelTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @mixin \Eloquent
 */
class ProductWarehouse extends Model implements Transformable
{
    use HasFactory, SoftDeletes;

    // Warehouse receipt product with price.
    const STATUS_ACTIVE = 'active';
    // All amount of product was issued away from warehouse.
    const STATUS_DISABLED = 'disabled';
    // When check issues all amount of product from any warehouse.
    const STATUS_REMOVED = 'removed';

    protected $fillable = ['amount', 'price', 'validFrom', 'validTo', 'status', 'product_id', 'warehouse_id'];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PriceLevel
 *
 * @property int $id
 * @property string $amount
 * @property string $price
 * @property string $validFrom
 * @property string $validTo
 * @property int $product_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\PriceLevelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereWarehouseId($value)
 * @mixin \Eloquent
 */
class PriceLevel extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'price', 'validFrom', 'validTo', 'product_id', 'warehouse_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

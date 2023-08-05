<?php

namespace App\Models;

use App\Transformers\CheckTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Check
 *
 * @property int $id
 * @property string $uuid
 * @property float $discount
 * @property int $warehouse_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereWarehouseId($value)
 * @mixin \Eloquent
 */
class Check extends Model implements Transformable
{
    use HasFactory;

    protected $fillable = ['discount', 'warehouse_id', 'user_id'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->as('product_check')
            ->using(CheckProduct::class)
            ->withPivot(['amount_before', 'amount_after', 'price_level_id', 'price']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function transformer(): string
    {
        return CheckTransformer::class;
    }
}

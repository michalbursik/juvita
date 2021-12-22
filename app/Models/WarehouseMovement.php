<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WarehouseMovement
 *
 * @property int $id
 * @property string $type
 * @property float $amount
 * @property string $price
 * @property int $product_id
 * @property int $user_id
 * @property int $warehouse_id
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseMovement whereWarehouseId($value)
 * @mixin \Eloquent
 */
class WarehouseMovement extends Model
{
    const TYPE_ISSUE = 'issue';
    const TYPE_RECEIPT = 'receipt';
//    const TYPE_TRANSMISSION = 'transmission';

    use HasFactory;

    protected $fillable = ['type', 'amount', 'price', 'product_id', 'warehouse_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getCreatedAtAttribute($created_at): string
    {
        return (new Carbon($created_at))->timezone('Europe/Prague')->format('d. m. Y H:i:s');
    }
}

<?php

namespace App\Models;

use App\Traits\Eventable;
use App\Transformers\CheckTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\Check
 *
 * @property int $id
 * @property string $uuid
 * @property float $discount
 * @property float|null $total_price
 * @property string $warehouse_uuid
 * @property string $user_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductCheck> $productChecks
 * @property-read int|null $product_checks_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereWarehouseUuid($value)
 * @mixin \Eloquent
 */
class Check extends Projection implements Transformable
{
    use HasFactory, Eventable;

    protected $fillable = ['discount', 'warehouse_uuid', 'user_uuid'];

    public function productChecks(): HasMany
    {
        return $this->hasMany(ProductCheck::class);
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

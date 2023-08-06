<?php

namespace App\Models;

use App\Enums\MovementTypeEnum;
use App\Traits\Eventable;
use App\Transformers\MovementTransformer;
use Carbon\Carbon;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\Movement
 *
 * @property int $id
 * @property string $uuid
 * @property MovementTypeEnum $type
 * @property float $amount
 * @property float $price
 * @property string $product_uuid
 * @property string $user_uuid
 * @property string|null $source_warehouse_uuid
 * @property string|null $target_warehouse_uuid
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse|null $sourceWarehouse
 * @property-read \App\Models\Warehouse|null $targetWarehouse
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereSourceWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereTargetWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUuid($value)
 * @mixin \Eloquent
 */
class Movement extends Projection implements Transformable
{
    use HasFactory, Eventable;

    protected $fillable = [
        'product_uuid',
        'source_warehouse_uuid',
        'target_warehouse_uuid',
        'user_uuid',
        'type',
        'amount',
        'price',
    ];

    protected $casts = [
        'type' => MovementTypeEnum::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sourceWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_uuid');
    }

    public function targetWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'target_warehouse_uuid');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getCreatedAtAttribute($created_at): string
    {
        return (new Carbon($created_at))
            ->timezone('Europe/Prague')
            ->format('d. m. Y H:i:s');
    }

    public function transformer(): string
    {
        return MovementTransformer::class;
    }
}

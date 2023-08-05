<?php

namespace App\Models;

use App\Enums\WarehouseTypeEnum;
use App\Events\ProductMoved;
use App\Events\ProductReceived;
use App\Traits\Eventable;
use App\Transformers\WarehouseTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property WarehouseTypeEnum $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Collection<int, \App\Models\Check> $checks
 * @property-read int|null $checks_count
 * @property-read Collection<int, \App\Models\Discount> $discounts
 * @property-read int|null $discounts_count
 * @property-read Collection<int, \App\Models\Movement> $issueMovements
 * @property-read int|null $issue_movements_count
 * @property-read Collection<int, \App\Models\WarehouseProduct> $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read Collection<int, \App\Models\WarehouseProduct> $products
 * @property-read int|null $products_count
 * @property-read Collection<int, \App\Models\Movement> $receiptMovements
 * @property-read int|null $receipt_movements_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\WarehouseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse withoutTrashed()
 * @mixin \Eloquent
 */
class Warehouse extends Projection implements Transformable
{
    use HasFactory, HasManyMergedRelation, SoftDeletes, Eventable;

    const TYPE_MAIN = 'warehouse';
    const TYPE_TEMPORARY = 'temporary_warehouse';
    const TYPE_INTERNAL = 'internal_warehouse';
    const TYPE_TRASH = 'trash_warehouse';
    protected $fillable = ['name', 'type'];

    protected $casts = [
        'type' => WarehouseTypeEnum::class
    ];

    public function receiveProduct(string $productUuid, float $price, float $amount): void
    {
        $user = Auth::user();
        event(
            new ProductReceived(
                $this->uuid, $productUuid, $user->uuid, $price, $amount
            )
        );
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function moveProduct(
        string $targetWarehouseUuid,
        string $productUuid,
        string $warehouse_product_price_uuid,
        float $amount
    ): void {
        $user = Auth::user();
        event(
            new ProductMoved(
                $this->uuid,
                $targetWarehouseUuid,
                $productUuid,
                $user->uuid,
                $warehouse_product_price_uuid,
                $amount
            )
        );
    }

    public function priceLevels(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    public function issueMovements(): HasMany
    {
        return $this->hasMany(Movement::class, 'issue_warehouse_id');
    }

    public function receiptMovements(): HasMany
    {
        return $this->hasMany(Movement::class, 'receipt_warehouse_id');
    }

    public function movements(): HasManyMerged
    {
        return $this->hasManyMerged(Movement::class, ['issue_warehouse_id', 'receipt_warehouse_id']);
    }

    public function activeProducts(): Collection
    {
        return $this->products()->where('products.active', true)->get();
    }

    public function products(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function transformer(): string
    {
        return WarehouseTransformer::class;
    }
}

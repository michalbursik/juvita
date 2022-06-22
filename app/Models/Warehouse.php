<?php

namespace App\Models;

use App\Transformers\WarehouseTransformer;
use Awobaz\Compoships\Compoships;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @property-read Collection|\App\Models\Movement[] $issueMovements
 * @property-read int|null $issue_movements_count
 * @property-read Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read Collection|\App\Models\Movement[] $receiptMovements
 * @property-read int|null $receipt_movements_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\WarehouseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read Collection|\App\Models\PriceLevel[] $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read Collection|\App\Models\Discount[] $discounts
 * @property-read int|null $discounts_count
 */
class Warehouse extends Model implements Transformable
{
    use HasFactory, HasManyMergedRelation, SoftDeletes;

    const TYPE_MAIN = 'warehouse';
    const TYPE_TEMPORARY = 'temporary_warehouse';
    const TYPE_INTERNAL = 'internal_warehouse';
    const TYPE_TRASH = 'trash_warehouse';

    protected $fillable = ['name', 'type'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->as('product_warehouse')
            ->withPivot(['amount', 'price']);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function priceLevels(): HasMany
    {
        return $this->hasMany(PriceLevel::class);
    }

    public function issueMovements(): HasMany
    {
        return $this->hasMany(Movement::class, 'issue_warehouse_id');
    }

    public function receiptMovements(): HasMany
    {
        return $this->hasMany(Movement::class, 'receipt_warehouse_id');
    }

    public function movements(): \Korridor\LaravelHasManyMerged\HasManyMerged
    {
        return $this->hasManyMerged(Movement::class, ['issue_warehouse_id', 'receipt_warehouse_id']);
    }

    public function activeProducts(): Collection
    {
        return $this->products()->where('products.active', true)->get();
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

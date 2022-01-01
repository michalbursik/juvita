<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User|null $user
 * @property-read Collection|\App\Models\WarehouseMovement[] $warehouseMovements
 * @property-read int|null $warehouse_movements_count
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
 */
class Warehouse extends Model
{
    use HasFactory;

    const TYPE_MAIN = 'warehouse';
    const TYPE_TEMPORARY = 'temporary_warehouse';
    const TYPE_INTERNAL = 'internal_warehouse';
    const TYPE_TRASH = 'trash_warehouse';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['amount', 'price']);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function warehouseMovements(): HasMany
    {
        return $this->hasMany(WarehouseMovement::class);
    }

    public function activeProducts(): Collection
    {
        return $this->products()->where('products.active', true)->get();
    }
}

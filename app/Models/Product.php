<?php

namespace App\Models;

use App\Traits\Eventable;
use App\Transformers\ProductTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string|null $origin
 * @property bool $active
 * @property int $order
 * @property string|null $image
 * @property string $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WarehouseProduct> $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 * @mixin \Eloquent
 */
class Product extends Projection implements Transformable
{
    use HasFactory, Eventable;

    const DEFAULT_UNIT = 'kg';
    const AVAILABLE_UNITS = ['kg', 'ks'];
    protected $fillable = ['name', 'origin', 'active', 'order', 'unit', 'image'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public static function getListOfAvailableUnits($separator = ','): string
    {
        return implode($separator, self::AVAILABLE_UNITS);
    }

    public static function getNextOrderNumber()
    {
        $product = Product::query()->orderByDesc('order')->first('order');

        return !empty($product) ? $product->order + 10 : 10;
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class);
    }

    public function priceLevels(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public function transformer(): string
    {
        return ProductTransformer::class;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CheckProduct
 *
 * @property int $id
 * @property float $amount_before
 * @property float $amount_after
 * @property int $check_id
 * @property int $product_id
 * @property int|null $price_level_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereAmountAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereAmountBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereCheckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct wherePriceLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct whereUpdatedAt($value)
 * @property string|null $price
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct wherePrice($value)
 * @mixin \Eloquent
 */
class CheckProduct extends Pivot
{
    use HasFactory;
}

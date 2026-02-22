<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Check
 *
 * @property int $id
 * @property int $warehouse_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereWarehouseId($value)
 * @mixin \Eloquent
 * @property string $discount
 * @method static \Illuminate\Database\Eloquent\Builder|Check whereDiscount($value)
 * @property-read \App\Models\CheckProduct|null $product_check
 * @method static \Database\Factories\CheckFactory factory($count = null, $state = [])
 */
	class Check extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\CheckProduct
 *
 * @property string $id
 * @property float $amount_before
 * @property float $amount_after
 * @property string $check_id
 * @property string $product_id
 * @property string|null $price_level_id
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
 * @mixin \Eloquent
 * @property string|null $price
 * @method static \Illuminate\Database\Eloquent\Builder|CheckProduct wherePrice($value)
 */
	class CheckProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Discount
 *
 * @property int $id
 * @property string $amount
 * @property int $warehouse_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereWarehouseId($value)
 * @mixin \Eloquent
 * @property string|null $note
 * @method static \Database\Factories\DiscountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereNote($value)
 */
	class Discount extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\Movement
 *
 * @property int $id
 * @property string $type
 * @property float $amount
 * @property string|null $price
 * @property int $product_id
 * @property int $user_id
 * @property int|null $issue_warehouse_id
 * @property int $receipt_warehouse_id
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Warehouse|null $issueWarehouse
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse $receiptWarehouse
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereIssueWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereReceiptWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUserId($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\MovementFactory factory($count = null, $state = [])
 */
	class Movement extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\PriceLevel
 *
 * @property int $id
 * @property string $amount
 * @property string $price
 * @property string $validFrom
 * @property string $validTo
 * @property string $status
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
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereWarehouseId($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Query\Builder|PriceLevel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceLevel whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PriceLevel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PriceLevel withoutTrashed()
 */
	class PriceLevel extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string|null $origin
 * @property int $active
 * @property int $order
 * @property string|null $image
 * @property string $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceLevel[] $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[] $movements
 * @property-read int|null $warehouse_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Warehouse[] $warehouses
 * @property-read int|null $warehouses_count
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
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
 * @mixin \Eloquent
 * @property-read int|null $movements_count
 */
	class Product extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property int $warehouse_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Warehouse $warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[] $movements
 * @property-read int|null $warehouse_movements_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWarehouseId($value)
 * @mixin \Eloquent
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discount[] $discounts
 * @property-read int|null $discounts_count
 */
	class User extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}

namespace App\Models{
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
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse withoutTrashed()
 */
	class Warehouse extends \Eloquent implements \Flugg\Responder\Contracts\Transformable {}
}


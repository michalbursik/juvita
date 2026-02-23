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
 * @property string $id
 * @property numeric $discount
 * @property string $warehouse_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CheckProduct|null $product_check
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Database\Factories\CheckFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check whereWarehouseId($value)
 */
	class Check extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CheckProduct
 *
 * @property string $id
 * @property numeric $amount_before
 * @property numeric $amount_after
 * @property numeric $price
 * @property string $check_id
 * @property string $product_id
 * @property string $price_level_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereAmountAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereAmountBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereCheckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct wherePriceLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckProduct whereUpdatedAt($value)
 */
	class CheckProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Discount
 *
 * @property string $id
 * @property numeric $amount
 * @property string|null $note
 * @property string $warehouse_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\DiscountStatus $status
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Database\Factories\DiscountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereWarehouseId($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Movement
 *
 * @property string $id
 * @property string $type
 * @property numeric $amount
 * @property numeric $price
 * @property string $product_id
 * @property string $user_id
 * @property string|null $issue_warehouse_id
 * @property string|null $receipt_warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $translated_type
 * @property-read \App\Models\Warehouse|null $issueWarehouse
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse|null $receiptWarehouse
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereIssueWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereReceiptWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereUserId($value)
 */
	class Movement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PriceLevel
 *
 * @property string $id
 * @property numeric $amount
 * @property numeric $price
 * @property string $validFrom
 * @property string $validTo
 * @property string $status
 * @property string $product_id
 * @property string $warehouse_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\PriceLevelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel whereWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceLevel withoutTrashed()
 */
	class PriceLevel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property string $id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceLevel> $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \App\Enums\UserRole $role
 * @property string $warehouse_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Discount> $discounts
 * @property-read int|null $discounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWarehouseId($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Warehouse
 *
 * @property string $id
 * @property string $name
 * @property \App\Enums\WarehouseType $type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $active
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Check> $checks
 * @property-read int|null $checks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Discount> $discounts
 * @property-read int|null $discounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movement> $issueMovements
 * @property-read int|null $issue_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceLevel> $priceLevels
 * @property-read int|null $price_levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movement> $receiptMovements
 * @property-read int|null $receipt_movements_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\WarehouseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse withoutTrashed()
 */
	class Warehouse extends \Eloquent {}
}


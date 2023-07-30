<?php

namespace App\Models;

use App\Transformers\MovementTransformer;
use Carbon\Carbon;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Movement
 *
 * @property int $id
 * @property string $uuid
 * @property string $type
 * @property float $amount
 * @property float|null $price
 * @property int $product_id
 * @property int $user_id
 * @property int|null $issue_warehouse_id
 * @property int|null $receipt_warehouse_id
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Warehouse|null $issueWarehouse
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Warehouse|null $receiptWarehouse
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MovementFactory factory($count = null, $state = [])
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
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUuid($value)
 * @mixin \Eloquent
 */
class Movement extends Model implements Transformable
{
    const TYPE_ISSUE = 'issue';
    const TYPE_RECEIPT = 'receipt';
    const TYPE_TRANSMISSION = 'transmission';

    const TYPE_CHECK = 'check'; // Check changes warehouse amounts

    use HasFactory;

    protected $fillable = ['type', 'amount', 'price', 'product_id', 'issue_warehouse_id', 'receipt_warehouse_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function issueWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiptWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
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

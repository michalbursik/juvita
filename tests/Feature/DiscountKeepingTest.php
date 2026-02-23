<?php

namespace Tests\Feature;

use App\Enums\DiscountStatus;
use App\Models\Discount;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountKeepingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::find(TestConstants::USER_ADMIN_ID);
        $this->warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
        $this->product = Product::find(TestConstants::PRODUCT_APPLE_ID);

        // Initial stock
        $this->priceLevel = PriceLevel::create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'amount' => 10,
            'price' => 50,
            'validFrom' => now(),
            'validTo' => now()->addWeek(),
            'status' => PriceLevel::STATUS_ACTIVE,
        ]);
        $this->warehouse->products()->updateExistingPivot($this->product->id, ['amount' => 10]);
    }

    public function test_discounts_are_kept_after_inventory_check(): void
    {
        // Create an active discount
        $discount = Discount::create([
            'amount' => 100,
            'note' => 'Keep this note',
            'warehouse_id' => $this->warehouse->id,
            'user_id' => $this->user->id,
            'status' => DiscountStatus::ACTIVE,
        ]);

        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'status' => 'active',
        ]);

        // Perform inventory check
        $response = $this->actingAs($this->user)->postJson('/api/warehouses/checks', [
            'warehouse_id' => $this->warehouse->id,
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'price_level_id' => $this->priceLevel->id,
                    'warehouse_id' => $this->warehouse->id,
                    'amount' => 10, // No change in stock
                ],
            ],
        ]);

        $response->assertStatus(200);

        // Verify discount still exists but is marked as applied
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'status' => 'applied',
            'note' => 'Keep this note',
        ]);

        // Verify check has the correct discount amount
        $this->assertDatabaseHas('checks', [
            'warehouse_id' => $this->warehouse->id,
            'discount' => -100,
        ]);
    }

    public function test_applied_discounts_are_not_picked_up_by_next_check(): void
    {
        // Create an applied discount from previous check
        Discount::create([
            'amount' => 100,
            'note' => 'Already applied',
            'warehouse_id' => $this->warehouse->id,
            'user_id' => $this->user->id,
            'status' => DiscountStatus::APPLIED,
        ]);

        // Create a new active discount
        $activeDiscount = Discount::create([
            'amount' => 50,
            'note' => 'New discount',
            'warehouse_id' => $this->warehouse->id,
            'user_id' => $this->user->id,
            'status' => DiscountStatus::ACTIVE,
        ]);

        // Perform inventory check
        $response = $this->actingAs($this->user)->postJson('/api/warehouses/checks', [
            'warehouse_id' => $this->warehouse->id,
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'price_level_id' => $this->priceLevel->id,
                    'warehouse_id' => $this->warehouse->id,
                    'amount' => 10,
                ],
            ],
        ]);

        $response->assertStatus(200);

        // Verify check only picked up the 50 Kč discount, not the 100 Kč one
        $this->assertDatabaseHas('checks', [
            'warehouse_id' => $this->warehouse->id,
            'discount' => -50,
        ]);

        // Verify new discount is now applied
        $this->assertEquals(DiscountStatus::APPLIED, $activeDiscount->fresh()->status);
    }
}

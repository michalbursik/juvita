<?php

namespace Tests\Unit\Domain\Warehouse;

use App\Domain\Warehouse\Events\StockIssued;
use App\Domain\Warehouse\Events\StockReceived;
use App\Domain\Warehouse\Events\StockTransferred;
use App\Domain\Warehouse\Projections\WarehouseProjector;
use App\Models\Movement;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\TestConstants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseProjectorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TestDataSeeder::class);
        $this->projector = new WarehouseProjector();
    }

    /** @test */
    public function it_projects_stock_received()
    {
        $event = new StockReceived(
            productId: TestConstants::PRODUCT_APPLE_ID,
            warehouseId: TestConstants::WAREHOUSE_MAIN_ID,
            amount: 50.5,
            price: 15.0,
            userId: TestConstants::USER_ADMIN_ID
        );

        $this->projector->onStockReceived($event);

        $this->assertDatabaseHas('movements', [
            'type' => Movement::TYPE_RECEIPT,
            'amount' => 50.5,
            'product_id' => TestConstants::PRODUCT_APPLE_ID,
            'receipt_warehouse_id' => TestConstants::WAREHOUSE_MAIN_ID,
        ]);

        $this->assertDatabaseHas('price_levels', [
            'product_id' => TestConstants::PRODUCT_APPLE_ID,
            'warehouse_id' => TestConstants::WAREHOUSE_MAIN_ID,
            'amount' => 50.5,
            'price' => 15.0,
        ]);

        $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
        $product = $warehouse->products()->find(TestConstants::PRODUCT_APPLE_ID);
        $this->assertEquals(50.5, $product->product_warehouse->amount);
    }

    /** @test */
    public function it_projects_stock_issued()
    {
        // Setup initial stock via projection
        $receive = new StockReceived(
            productId: TestConstants::PRODUCT_APPLE_ID,
            warehouseId: TestConstants::WAREHOUSE_MAIN_ID,
            amount: 100,
            price: 10,
            userId: TestConstants::USER_ADMIN_ID
        );
        $this->projector->onStockReceived($receive);
        $priceLevel = PriceLevel::first();

        $event = new StockIssued(
            productId: TestConstants::PRODUCT_APPLE_ID,
            warehouseId: TestConstants::WAREHOUSE_MAIN_ID,
            priceLevelId: $priceLevel->id,
            amount: 40,
            userId: TestConstants::USER_ADMIN_ID
        );

        $this->projector->onStockIssued($event);

        $this->assertDatabaseHas('movements', [
            'type' => Movement::TYPE_ISSUE,
            'amount' => 40,
            'product_id' => TestConstants::PRODUCT_APPLE_ID,
            'issue_warehouse_id' => TestConstants::WAREHOUSE_MAIN_ID,
        ]);

        $this->assertEquals(60, $priceLevel->fresh()->amount);

        $warehouse = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
        $product = $warehouse->products()->find(TestConstants::PRODUCT_APPLE_ID);
        $this->assertEquals(60, $product->product_warehouse->amount);
    }

    /** @test */
    public function it_projects_stock_transferred()
    {
        // Setup initial stock in Main
        $receive = new StockReceived(
            productId: TestConstants::PRODUCT_APPLE_ID,
            warehouseId: TestConstants::WAREHOUSE_MAIN_ID,
            amount: 100,
            price: 10,
            userId: TestConstants::USER_ADMIN_ID
        );
        $this->projector->onStockReceived($receive);
        $priceLevel = PriceLevel::first();

        $event = new StockTransferred(
            productId: TestConstants::PRODUCT_APPLE_ID,
            fromWarehouseId: TestConstants::WAREHOUSE_MAIN_ID,
            toWarehouseId: TestConstants::WAREHOUSE_TRASH_ID,
            priceLevelId: $priceLevel->id,
            amount: 30,
            userId: TestConstants::USER_ADMIN_ID
        );

        $this->projector->onStockTransferred($event);

        // Check Source
        $this->assertEquals(70, $priceLevel->fresh()->amount);
        $fromW = Warehouse::find(TestConstants::WAREHOUSE_MAIN_ID);
        $this->assertEquals(70, $fromW->products()->find(TestConstants::PRODUCT_APPLE_ID)->product_warehouse->amount);

        // Check Destination
        $toW = Warehouse::find(TestConstants::WAREHOUSE_TRASH_ID);
        $this->assertEquals(30, $toW->products()->find(TestConstants::PRODUCT_APPLE_ID)->product_warehouse->amount);
        $this->assertDatabaseHas('price_levels', [
            'warehouse_id' => TestConstants::WAREHOUSE_TRASH_ID,
            'amount' => 30,
            'price' => 10
        ]);
    }
}

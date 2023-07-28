<?php

use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\Movement;
use App\Models\Warehouse;
use App\Services\RebuildMovementsService;
use App\Services\WarehouseService;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertEquals;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->warehouseManager = new WarehouseManager();
    $this->pricesManager = new PricesManager();
});


test('warehouse can receive movement', function () {
    /** @var Warehouse $warehouse */
    $warehouse = Warehouse::factory()->create();
    $product = initiateProductsOnWarehouse($warehouse);

    $movements = Movement::factory()
        ->receiptTo($warehouse)
        ->count(2)
        ->state(
            new Sequence(
                ['amount' => 5, 'price' => 100],
                ['amount' => 10, 'price' => 25],
            )
        )
        ->create([
            'product_id' => $product->id,
        ]);

    /** @var Movement $movement */
    foreach ($movements as $movement) {
        $warehouseProduct = $this->warehouseManager->receipt($movement);
//        $this->pricesManager->receipt($movement);

        expect($warehouseProduct->product_warehouse->amount)->toBeFloat()->toBe((float) $movement->amount);
    }

//    $warehouseProduct = $warehouse
//        ->products()
//        ->where('id', $product->id)
//        ->first();



    expect($warehouse->products)->toHaveCount(2)
        ->and($warehouse->priceLevels)->toHaveCount(2);
});

it('can rebuild movements on different warehouse', function () {
    /** @var Warehouse $warehouseFrom */
    $warehouseFrom = Warehouse::factory()->main()->create();
    $product = initiateProductsOnWarehouse($warehouseFrom);


    // Generate movements
    /** @var Movement $movement */
    $movement = Movement::factory()->receiptTo($warehouseFrom)->create([
        'product_id' => $product->id,
    ]);

    $this->warehouseManager->receipt($movement);
    $this->pricesManager->receipt($movement);

    $warehouseFrom->refresh();

    $warehouseFromProduct = $warehouseFrom->products()->find($product->id);

    expect($warehouseFromProduct->product_warehouse->amount)->toBeFloat()->toBe(100.0)
        ->and($warehouseFromProduct->product_warehouse->price)->toBeFloat()->toBe(10.0);

    // Transfer to
    /** @var Warehouse $warehouseTo */
    $warehouseTo = Warehouse::factory()->main()->create();

    $rebuildMovementsService = new RebuildMovementsService(
        $warehouseFrom, $warehouseTo, $product
    );

    $rebuildMovementsService->setInitialAmount(0)
//                            ->setInitialPrice(50)
        ->rebuild();

    $warehouseFromProduct->refresh();
    $warehouseToProduct = $warehouseTo->products()->find($product->id);

    assertEquals($warehouseFromProduct->product_warehouse->amount, $warehouseToProduct->product_warehouse->amount);
    assertEquals($warehouseFromProduct->product_warehouse->price, $warehouseToProduct->product_warehouse->price);
});

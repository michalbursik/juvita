<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PriceLevelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\WarehouseProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(WarehouseController::class)
        ->prefix('warehouses')
        ->name('warehouses.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('trash', 'trash')->name('trash');
        Route::get('{warehouse}', 'show')->name('show');

        Route::middleware('admin')->group(function () {
            Route::post('', 'store')->name('store');
            Route::patch('{warehouse}', 'update')->name('update');
            Route::delete('{warehouse}', 'destroy')->name('destroy');
        });
    });

    Route::controller(WarehouseProductController::class)
        ->prefix('warehouse_products')
        ->name('warehouse_products.')->group(function () {
        Route::post('trash', 'trash')->name('trash');
        Route::post('receive', 'receive')->name('receive');
        Route::post('move', 'move')->name('move');

        // Route::get('{warehouse}/products/total_amount', 'totalAmount')->name('total_amount');
        // Route::get('{warehouse}/products/{product}', 'getProducts')->name('getProducts');
        Route::get('{warehouse_product}', 'show')->name('show');
    });

    Route::controller(MovementController::class)
        ->prefix('movements')
        ->name('movements.')->group(function () {
        Route::get('', 'index')->name('index');
    });

    Route::controller(UserController::class)
        ->prefix('users')
        ->name('users.')->group(function () {
            Route::get('', 'index')->name('index');
        });

    // warehouse_products/{warehouse_product_uuid}/index => list of warehouseProducts => uuids
    // warehouse_products/{warehouse_product_uuid}/receive/
    // warehouse_products/{warehouse_product_uuid}/move/{warehouse_product_uuid}




    // Route::resource('discounts', DiscountController::class);


//    Route::get('warehouses/{warehouse}/products/{product}', [WarehouseController::class, 'showProduct'])
//        ->name('warehouses.products.show');
//
//    Route::get('warehouses/{warehouse}/products/{product}/receipt', [WarehouseController::class, 'receipt'])
//        ->name('warehouses.products.receipt');
//    Route::get('warehouses/{warehouse}/products/{product}/issue', [WarehouseController::class, 'issue'])
//        ->name('warehouses.products.issue');
//    Route::get('warehouses/{warehouse}/products/{product}/transmission', [WarehouseController::class, 'transmission'])
//        ->name('warehouses.products.transmission');

//    Route::resource('users', UserController::class);
//
//    Route::resource('priceLevels', PriceLevelController::class, ['only' => 'index']);
//    Route::get('movements/fetchAllAmounts', [MovementController::class, 'fetchAllAmounts'])->name('movements.fetchAllAmounts');
//
//    Route::middleware('admin')->group(function () {
//        Route::get('/overviews', [OverviewController::class, 'index'])->name('overview.index');
//
//        Route::get('warehouses/checks/products', [CheckController::class, 'fetchAllProducts'])->name('checks.fetchAllProducts');
//
//        Route::resource('warehouses/checks', CheckController::class);
//        Route::resource('warehouses', WarehouseController::class, ['except' => ['show', 'index']]);
//
//        Route::resource('movements', MovementController::class, ['except' => ['store', 'index']]);
//
//        Route::get('products/nextOrder', [ProductController::class, 'nextOrder'])->name('products.nextOrder');
//        Route::resource('products', ProductController::class, ['except' => ['index', 'show']]);
//    });
//

    Route::resource('products', ProductController::class, ['only' => ['index', 'show']]);
});

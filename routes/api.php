<?php

use App\Http\Controllers\CheckController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PriceLevelController;
use App\Http\Controllers\ProductCheckController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseProductController;
use Illuminate\Http\Request;
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

    Route::controller(ProductController::class)
        ->prefix('products')
        ->middleware('admin')
        ->name('products.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('nextOrder', 'nextOrder')->name('nextOrder');
            Route::get('{product}', 'show')->name('show');
            Route::post('', 'store')->name('store');
            Route::patch('{product}', 'update')->name('update');
            Route::delete('{product}', 'destroy')->name('destroy');
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

    Route::controller(CheckController::class)
        ->prefix('checks')
        ->middleware('admin')
        ->name('checks.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('products', 'fetchAllProductPrices')->name('fetchAllProductPrices');
            Route::get('{check}', 'show')->name('show');
            Route::post('', 'store')->name('store');
        });

    Route::controller(ProductCheckController::class)
        ->prefix('product_checks')
        ->middleware('admin')
        ->name('product_checks.')->group(function () {
            Route::get('', 'index')->name('index');
        });

    Route::controller(DiscountController::class)
        ->prefix('discounts')
        ->middleware('admin')
        ->name('discounts.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('', 'store')->name('store');
            Route::get('{discount}', 'show')->name('show');
            Route::patch('{discount}', 'update')->name('update');
            Route::delete('{discount}', 'destroy')->name('destroy');
        });

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
//        Route::resource('warehouses', WarehouseController::class, ['except' => ['show', 'index']]);
//
//        Route::resource('movements', MovementController::class, ['except' => ['store', 'index']]);
//
//        Route::resource('products', ProductController::class, ['except' => ['index', 'show']]);
//    });
//

//    Route::resource('products', ProductController::class, ['only' => ['index', 'show']]);
});

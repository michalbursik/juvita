<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PriceLevelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('discounts', DiscountController::class)->names([
        'index' => 'api.discounts.index',
        'store' => 'api.discounts.store',
        'show' => 'api.discounts.show',
        'update' => 'api.discounts.update',
        'destroy' => 'api.discounts.destroy',
    ]);
    Route::get('warehouses/trash', [WarehouseController::class, 'trash'])->name('api.warehouses.trash');
    Route::get('warehouses/movements', [MovementController::class, 'index'])->name('api.movements.index');

    Route::get('warehouses/{warehouse}/products/{product}', [WarehouseController::class, 'showProduct'])
        ->name('api.warehouses.products.show');

    Route::get('warehouses/{warehouse}/products/{product}/receipt', [WarehouseController::class, 'receipt'])
        ->name('api.warehouses.products.receipt');
    Route::get('warehouses/{warehouse}/products/{product}/issue', [WarehouseController::class, 'issue'])
        ->name('api.warehouses.products.issue');
    Route::get('warehouses/{warehouse}/products/{product}/transmission', [WarehouseController::class, 'transmission'])
        ->name('api.warehouses.products.transmission');

    Route::post('warehouses/movements/transmission', [MovementController::class, 'transmission'])
        ->name('api.movements.transmission');

    Route::post('warehouses/movements/trash', [MovementController::class, 'trash'])->name('api.movements.trash');
    Route::post('warehouses/movements/receipt', [MovementController::class, 'receipt'])->name('api.movements.receipt');

    Route::resource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);

    Route::resource('priceLevels', PriceLevelController::class, ['only' => 'index'])->names([
        'index' => 'api.priceLevels.index',
    ]);
    Route::get('movements/fetchAllAmounts', [MovementController::class, 'fetchAllAmounts'])->name('api.movements.fetchAllAmounts');
    Route::resource('movements', MovementController::class, ['only' => 'index'])->names([
        'index' => 'api.movements.list',
    ]);

    Route::middleware('admin')->group(function () {
        Route::get('/overviews', [OverviewController::class, 'index'])->name('api.overview.index');

        Route::get('warehouses/checks/products', [CheckController::class, 'fetchAllProducts'])->name('api.checks.fetchAllProducts');

        Route::resource('warehouses/checks', CheckController::class)->names([
            'index' => 'api.checks.index',
            'store' => 'api.checks.store',
            'show' => 'api.checks.show',
            'update' => 'api.checks.update',
            'destroy' => 'api.checks.destroy',
        ]);
        Route::resource('warehouses', WarehouseController::class, ['except' => ['show', 'index']])->names([
            'store' => 'api.warehouses.store',
            'update' => 'api.warehouses.update',
            'destroy' => 'api.warehouses.destroy',
        ]);

        Route::resource('movements', MovementController::class, ['except' => ['store', 'index']])->names([
            'show' => 'api.movements.show',
            'update' => 'api.movements.update',
            'destroy' => 'api.movements.destroy',
        ]);

        Route::get('products/nextOrder', [ProductController::class, 'nextOrder'])->name('api.products.nextOrder');
        Route::resource('products', ProductController::class, ['except' => ['index', 'show']])->names([
            'store' => 'api.products.store',
            'update' => 'api.products.update',
            'destroy' => 'api.products.destroy',
        ]);
    });

    Route::resource('warehouses', WarehouseController::class, ['only' => ['show', 'index']])->names([
        'index' => 'api.warehouses.index',
        'show' => 'api.warehouses.show',
    ]);
    Route::resource('products', ProductController::class, ['only' => ['index', 'show']])->names([
        'index' => 'api.products.index',
        'show' => 'api.products.show',
    ]);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseMovementController;
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

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('warehouses/trash', [WarehouseController::class, 'trash']);
    Route::resource('warehouses', WarehouseController::class, ['only' => 'show']);

    Route::get('warehouses/{warehouse}/products/{product}', [WarehouseController::class, 'showProduct'])
        ->name('warehouses.products.show');

    Route::get('warehouses/{warehouse}/products/{product}/receipt', [WarehouseController::class, 'receipt'])
        ->name('warehouses.products.receipt');
    Route::get('warehouses/{warehouse}/products/{product}/issue', [WarehouseController::class, 'issue'])
        ->name('warehouses.products.issue');
    Route::get('warehouses/{warehouse}/products/{product}/transmission', [WarehouseController::class, 'transmission'])
        ->name('warehouses.products.transmission');
    Route::post('warehouse_movements/transmission', [WarehouseMovementController::class, 'transmission'])
        ->name('warehouse_movements.transmission');


    Route::get('warehouse_movements', [WarehouseMovementController::class, 'index'])->name('warehouse_movements.index');

    Route::post('warehouse_movements/issue', [WarehouseMovementController::class, 'issue'])->name('warehouse_movements.issue');
    Route::post('warehouse_movements/receipt', [WarehouseMovementController::class, 'receipt'])->name('warehouse_movements.receipt');


    Route::resource('users', UserController::class);

    Route::middleware('admin')->group(function () {
        Route::resource('warehouses', WarehouseController::class, ['except' => 'show']);

        Route::resource('warehouse_movements', WarehouseMovementController::class, ['except' => 'store']);

        Route::get('products/nextOrder', [ProductController::class, 'nextOrder'])->name('products.nextOrder');
        Route::resource('products', ProductController::class);
    });
});


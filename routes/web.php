<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseMovementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect('/warehouses');
});

Route::middleware('auth')->group(function () {
    Route::resource('warehouses', WarehouseController::class, ['only' => 'show']);
    Route::get('warehouses/{warehouse}/products/{product}/receipt', [WarehouseController::class, 'receipt'])
        ->name('warehouses.products.receipt');
    Route::get('warehouses/{warehouse}/products/{product}/issue', [WarehouseController::class, 'issue'])
        ->name('warehouses.products.issue');
    Route::get('warehouses/{warehouse}/products/{product}/transmission', [WarehouseController::class, 'transmission'])
        ->name('warehouses.products.transmission');
    Route::post('warehouse-movements/transmission', [WarehouseMovementController::class, 'transmission'])
        ->name('warehouse-movements.transmission');
    Route::resource('warehouse-movements', WarehouseMovementController::class, ['only' => 'store']);

    Route::middleware('admin')->group(function () {
        Route::resource('warehouses', WarehouseController::class, ['except' => 'show']);

        Route::resource('warehouse-movements', WarehouseMovementController::class, ['except' => 'store']);

        Route::resource('products', ProductController::class);
    });
});

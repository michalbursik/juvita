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

\Illuminate\Support\Facades\Log::debug(__FILE__);

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('discounts', DiscountController::class);
    Route::get('warehouses/trash', [WarehouseController::class, 'trash']);
    Route::get('warehouses/movements', [MovementController::class, 'index'])->name('movements.index');


    Route::get('warehouses/{warehouse}/products/{product}', [WarehouseController::class, 'showProduct'])
        ->name('warehouses.products.show');

    Route::get('warehouses/{warehouse}/products/{product}/receipt', [WarehouseController::class, 'receipt'])
        ->name('warehouses.products.receipt');
    Route::get('warehouses/{warehouse}/products/{product}/issue', [WarehouseController::class, 'issue'])
        ->name('warehouses.products.issue');
    Route::get('warehouses/{warehouse}/products/{product}/transmission', [WarehouseController::class, 'transmission'])
        ->name('warehouses.products.transmission');

    \Illuminate\Support\Facades\Log::debug(__FILE__ . ' before _---------_');
    Route::post('warehouses/movements/transmission', [MovementController::class, 'transmission'])
        ->name('movements.transmission');
    \Illuminate\Support\Facades\Log::debug(__FILE__ . ' after _-----------_');

    Route::post('warehouses/movements/trash', [MovementController::class, 'trash'])->name('movements.trash');
    Route::post('warehouses/movements/receipt', [MovementController::class, 'receipt'])->name('movements.receipt');

    Route::resource('users', UserController::class);

    Route::resource('priceLevels', PriceLevelController::class, ['only' => 'index']);
    Route::get('movements/fetchAllAmounts', [MovementController::class, 'fetchAllAmounts'])->name('movements.fetchAllAmounts');
    Route::resource('movements', MovementController::class, ['only' => 'index']);

    Route::middleware('admin')->group(function () {
        Route::get('/overviews', [OverviewController::class, 'index'])->name('overview.index');

        Route::get('warehouses/checks/products', [CheckController::class, 'fetchAllProducts'])->name('checks.fetchAllProducts');

        Route::resource('warehouses/checks', CheckController::class);
        Route::resource('warehouses', WarehouseController::class, ['except' => ['show', 'index']]);

        Route::resource('movements', MovementController::class, ['except' => ['store', 'index']]);

        Route::get('products/nextOrder', [ProductController::class, 'nextOrder'])->name('products.nextOrder');
        Route::resource('products', ProductController::class, ['except' => ['index', 'show']]);
    });

    Route::resource('warehouses', WarehouseController::class, ['only' => ['show', 'index']]);
    Route::resource('products', ProductController::class, ['only' => ['index', 'show']]);
});

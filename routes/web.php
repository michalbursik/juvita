<?php

use App\Enums\WarehouseType;
use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Checks\Create as ChecksCreate;
use App\Livewire\Checks\Index as ChecksIndex;
use App\Livewire\Checks\Show as ChecksShow;
use App\Livewire\Discounts\Index as DiscountsIndex;
use App\Livewire\Movements\Index as MovementsIndex;
use App\Livewire\Overviews\Index as OverviewsIndex;
use App\Livewire\Products\Show;
use App\Livewire\Warehouses\Index as WarehousesIndex;
use App\Livewire\Warehouses\Products\Issue as ProductIssue;
use App\Livewire\Warehouses\Products\Receipt as ProductReceipt;
use App\Livewire\Warehouses\Products\Transmission as ProductTransmission;
use App\Livewire\Warehouses\Show as WarehousesShow;
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

// Auth Routes
Route::post('login', [AuthController::class, 'login'])
    ->name('api.login')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('login', Login::class)->name('login');
Route::post('logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// App Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('warehouses.index');
        }

        return redirect()->route('warehouses.show', auth()->user()->warehouse_id);
    });

    Route::get('warehouses', WarehousesIndex::class)->name('warehouses.index');
    Route::get('warehouses/create', \App\Livewire\Warehouses\Create::class)->name('warehouses.create');
    Route::get('warehouses/{warehouse}', WarehousesShow::class)->name('warehouses.show');
    Route::get('warehouses/{warehouse}/edit', \App\Livewire\Warehouses\Edit::class)->name('warehouses.edit');
    Route::get('warehouses/trash', function () {
        $trashWarehouse = \App\Models\Warehouse::where('type', WarehouseType::TRASH)->first();
        if ($trashWarehouse) {
            return redirect()->route('warehouses.show', $trashWarehouse->id);
        }

        return 'Trash warehouse not found';
    })->name('warehouses.trash');

    Route::get('warehouses/{warehouse}/products/{product}', Show::class)->name('warehouses.products.show');
    Route::get('warehouses/{warehouse}/products/{product}/receipt', ProductReceipt::class)->name('warehouses.products.receipt');
    Route::get('warehouses/{warehouse}/products/{product}/issue', ProductIssue::class)->name('warehouses.products.issue');
    Route::get('warehouses/{warehouse}/products/{product}/transmission', ProductTransmission::class)->name('warehouses.products.transmission');

    Route::get('overviews', OverviewsIndex::class)->name('overviews.index');
    Route::get('movements', MovementsIndex::class)->name('movements.index');
    Route::get('discounts', DiscountsIndex::class)->name('discounts.index');
    Route::get('discounts/create', \App\Livewire\Discounts\Create::class)->name('discounts.create');
    Route::get('discounts/{discount}/edit', \App\Livewire\Discounts\Edit::class)->name('discounts.edit');
    Route::get('users', \App\Livewire\Users\Index::class)->name('users.index');
    Route::get('users/create', \App\Livewire\Users\Create::class)->name('users.create');
    Route::get('users/{user}/edit', \App\Livewire\Users\Edit::class)->name('users.edit');

    Route::get('checks', ChecksIndex::class)->name('checks.index');
    Route::get('checks/create', ChecksCreate::class)->name('checks.create');
    Route::get('checks/{check}', ChecksShow::class)->name('checks.show');

    Route::get('products', \App\Livewire\Products\Index::class)->name('products.index');
    Route::get('products/create', \App\Livewire\Products\Create::class)->name('products.create');
    Route::get('products/{product}/edit', \App\Livewire\Products\Edit::class)->name('products.edit');
});

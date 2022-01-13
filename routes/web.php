<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NuxtController;
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

//Route::get('/sw.js', function () {
//    \Illuminate\Support\Facades\Log::debug('WEB');
//
//    return response(file_get_contents(asset('/sw.js')), 200, [
//        'Content-Type' => 'text/javascript',
//        'Cache-Control' => 'public, max-age=3600',
//    ]);
//});

// API ROUTES
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// --------------

Route::get('/{any?}', [NuxtController::class, 'nuxtMethod'])
    ->where('any', '^(?!(api|nova|telescope|sw.js)).*$');

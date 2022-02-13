<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NuxtController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MovementController;
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

// API ROUTES
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// --------------

Route::get('/{any?}', [NuxtController::class, 'nuxtMethod'])
    ->where('any', '^(?!(api|nova|telescope|vapor|vapor-ui|sw.js)).*$');

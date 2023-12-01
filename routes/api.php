<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Categories\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Shop\ShopController;
use App\Http\Controllers\Api\User\UserController;
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

/**
 * Router Auth
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('me', [AuthController::class, 'me']);
});

/**
 * Router User
 */
Route::prefix('v1')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'selectById']);
    Route::delete('/users/{id}', [UserController::class, 'removeUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
});

/**
 * Router Categories
 */
Route::prefix('v1')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'create']);
});

/**
 * Router Shop
 */
Route::prefix('v1')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/shops', [ShopController::class, 'create']);
});

/**
 * Router Product
 */
Route::prefix('v1')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'create']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/categories/{categoryId}', [ProductController::class, 'getProductsByCategory']);
    Route::get('/products/shops/{shopId}', [ProductController::class, 'getProductsByShopId']);
});

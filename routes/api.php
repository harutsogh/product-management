<?php

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::prefix('category')->group(function(){
        Route::get('/', [\App\Http\Controllers\CategoryController::class, 'paginated']);
        Route::delete('/{id}', [\App\Http\Controllers\CategoryController::class, 'delete']);
        Route::post('/', [\App\Http\Controllers\CategoryController::class, 'create']);
        Route::put('/{id}', [\App\Http\Controllers\CategoryController::class, 'update']);
    });

    Route::prefix('product')->group(function(){
        Route::get('/', [\App\Http\Controllers\ProductController::class, 'paginated']);
        Route::get('/category-tree', [\App\Http\Controllers\ProductController::class, 'categoryTree']);
        Route::get('/count', [\App\Http\Controllers\ProductController::class, 'totalCount']);
        Route::delete('/{id}', [\App\Http\Controllers\ProductController::class, 'delete']);
        Route::post('/', [\App\Http\Controllers\ProductController::class, 'create']);
        Route::get('/{id}', [\App\Http\Controllers\ProductController::class, 'getSingle']);
        Route::put('/{id}', [\App\Http\Controllers\ProductController::class, 'update']);
    });

    Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

});

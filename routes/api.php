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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('item', [\App\Http\Controllers\Admin\Api\AdminItemsController::class, 'index']);

Route::post('item', [\App\Http\Controllers\Admin\Api\AdminItemsController::class, 'store']);

Route::get('item/{id}', [\App\Http\Controllers\Admin\Api\AdminItemsController::class, 'show']);

Route::put('item/{id}', [\App\Http\Controllers\Admin\Api\AdminItemsController::class, 'update']);

Route::delete('item/{id}', [\App\Http\Controllers\Admin\Api\AdminItemsController::class, 'destroy']);

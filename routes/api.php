<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('inventories', App\Http\Controllers\InventoryController::class);
    Route::apiResource('inventories.item', App\Http\Controllers\ItemController::class);    
});

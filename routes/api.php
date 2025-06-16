<?php

use App\Http\Middleware\Cors;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;

Route::prefix('/api')->middleware([Cors::class, 'auth:sanctum'])->group(function() {
    Route::get('/test', [AuthController::class, 'test']);
    Route::apiResource('products', ProductController::class);
});

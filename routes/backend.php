<?php

use App\Http\Middleware\RoleMiddleware;


Route::prefix('/dashboard')->middleware(RoleMiddleware::class. ':user')->group(function() {
    Route::inertia('/', 'backend/Index')->name('pages.dashboard');
    Route::inertia('/product', 'backend/product/Index');
});

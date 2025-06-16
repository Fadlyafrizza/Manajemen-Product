<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\RoleMiddleware;

Route::inertia('/', 'frontend/Index')->middleware([RoleMiddleware::class.':admin'])->name('pages.home');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function() {
        Route::get('/login', 'login');
        Route::post('/login', 'login_post');
        Route::get('/register', 'register');
        Route::post('/register', 'register_post');
    });
    Route::post('/logout', 'logout');
});

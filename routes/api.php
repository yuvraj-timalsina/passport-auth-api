<?php

use App\Http\Controllers\Api\PassportController;
use Illuminate\Support\Facades\Route;

/** Auth Routes */
Route::controller(PassportController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');

    /** API Routes */
    Route::middleware('auth:api')->group(function () {
        Route::post('user-detail', 'userDetail');
        Route::post('logout', 'logout');
    });
});

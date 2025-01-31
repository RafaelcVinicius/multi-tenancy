<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tenants\TenantController;
use App\Http\Controllers\Users\UserContactController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/token', [AuthController::class, 'token']);
    });

    Route::prefix('users')->group(function () {
        Route::get('logged', [UserController::class, 'logged']);
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('users.contacts', UserContactController::class);
});

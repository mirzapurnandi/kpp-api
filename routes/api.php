<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    'middleware' => 'api', 'prefix' => 'v1'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group([
    'middleware' => ['auth:api'], 'prefix' => 'v1'
], function ($router) {
    Route::get('me', [AuthController::class, 'index']);
    Route::post('konfirmasi', [AuthController::class, 'konfirmasi']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoatController;
use App\Http\Controllers\UserController;

Route::group([
    'middleware' => 'api', 'prefix' => 'v1'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'middleware' => ['auth:api'], 'prefix' => 'v1'
], function () {
    Route::get('me', [AuthController::class, 'index']);
    Route::post('konfirmasi', [AuthController::class, 'konfirmasi']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for User
Route::group([
    'middleware' => ['auth:api', 'cekstatus'], 'prefix' => 'v1'
], function () {
    //Route::get('boat/index', [BoatController::class, 'index']);
    Route::post('boat/insert', [BoatController::class, 'insert']);
});

// Route for Admin
Route::group([
    'middleware' => ['auth:api', 'ceklevel:admin'], 'prefix' => 'v1/admin'
], function () {
    Route::get('index', [UserController::class, 'index']);
    Route::post('verifikasi', [UserController::class, 'verifikasi']);
    Route::post('boat/verifikasi', [BoatController::class, 'verifikasi']);
});

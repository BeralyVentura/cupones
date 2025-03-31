<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CouponController;

Route::get('/test', function () {
    return response()->json(['status' => 'API funcionando']);
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('businesses', BusinessController::class)->only(['index']);
    Route::apiResource('coupons', CouponController::class)->only(['index']);
});
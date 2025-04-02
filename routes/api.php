<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CouponController;

// Ruta de prueba pública
Route::get('/test', function () {
    return response()->json(['status' => 'API funcionando']);
});

// Rutas públicas de autenticación (NO requieren token)
Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Rutas protegidas comunes para cualquier usuario autenticado
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
});

// Rutas exclusivas para administradores
Route::prefix('v1')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Rutas exclusivas para empresas
Route::prefix('v1')->middleware(['auth:sanctum', 'role:empresa'])->group(function () {
    Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
    Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/{id}', [BusinessController::class, 'show'])->name('businesses.show');
    Route::put('/businesses/{id}', [BusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{id}', [BusinessController::class, 'destroy'])->name('businesses.destroy');

    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
});

// Rutas exclusivas para usuarios finales
Route::prefix('v1')->middleware(['auth:sanctum', 'role:usuario'])->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->name('coupons.show');
    Route::post('/coupons/{id}/redeem', [CouponController::class, 'redeem'])->name('coupons.redeem');
});

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

// Rutas protegidas con Sanctum (Requieren autenticación)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Ruta para cerrar sesión
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Obtener usuario autenticado
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

    // Usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Negocios
    Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
    Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/{id}', [BusinessController::class, 'show'])->name('businesses.show');
    Route::put('/businesses/{id}', [BusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{id}', [BusinessController::class, 'destroy'])->name('businesses.destroy');

    // Cupones
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->name('coupons.show');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/coupons/{id}/redeem', [CouponController::class, 'redeem'])->name('coupons.redeem'); // Canje de cupón
});

Route::middleware('auth:sanctum')->get('/v1/users', [UserController::class, 'index']);
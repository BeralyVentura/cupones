<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CouponController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🟢 Ruta pública para probar que la API funciona
Route::get('/test', function () {
    return response()->json(['status' => 'API funcionando']);
});

// 🔐 Autenticación pública (sin token)
Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// 🔐 Rutas protegidas para cualquier usuario autenticado
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
    Route::get('/businesses', [AuthController::class, 'listAllBusinesses'])->name('auth.businesses.listAllBusinesses');

});

// 🔒 Rutas para ADMINISTRADOR
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:Administrador'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// 🔒 Rutas para EMPRESAS
Route::prefix('v1/empresa')->middleware(['auth:sanctum', 'role:Empresa'])->group(function () {
    // Negocios
    Route::post('/businesses', [BusinessController::class, 'store'])->name('empresa.businesses.store');
    Route::get('/businesses/{id}', [BusinessController::class, 'show'])->name('empresa.businesses.show');
    Route::put('/businesses/{id}', action: [BusinessController::class, 'update'])->name('empresa.businesses.update');
    Route::delete('/businesses/{id}', [BusinessController::class, 'destroy'])->name('empresa.businesses.destroy');

    // Cupones
    Route::post('/coupons', [CouponController::class, 'store'])->name('empresa.coupons.store');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('empresa.coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('empresa.coupons.destroy');
});

// 🔒 Rutas para USUARIOS (Clientes)
Route::prefix('v1/usuario')->middleware(['auth:sanctum', 'role:Usuario'])->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('usuario.coupons.index');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->name('usuario.coupons.show');
    Route::post('/coupons/{id}/redeem', [CouponController::class, 'redeem'])->name('usuario.coupons.redeem');
});

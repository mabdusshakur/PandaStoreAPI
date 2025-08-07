<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/orders', [App\Http\Controllers\Api\User\OrderController::class, 'index']);
    Route::post('/orders', [App\Http\Controllers\Api\User\OrderController::class, 'process']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('products', App\Http\Controllers\Api\Admin\ProductController::class);
});


Route::post('/payment/success', [App\Http\Controllers\Api\User\OrderController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [App\Http\Controllers\Api\User\OrderController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel', [App\Http\Controllers\Api\User\OrderController::class, 'cancel'])->name('payment.cancel');
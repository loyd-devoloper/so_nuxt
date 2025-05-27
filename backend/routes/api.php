<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('login',[AuthController::class,'login'])->middleware(['guest']);
Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::post('/qad/login', [AuthController::class, 'qadLogin']);
    Route::get('/otp-data/{verification_id}', [AuthController::class, 'getOtp']);
    Route::post('/otp-verification', [AuthController::class, 'verifyOtp']);
    Route::put('/otp-resend-verification', [AuthController::class, 'resendOtp']);
});

Route::middleware(['auth:sanctum','qad'])->prefix('qad')->group(function () {
    Route::get('/userInfo', [AuthController::class, 'qadInfo']);
//    Route::post('/logout', [AuthController::class, 'logout']);
});

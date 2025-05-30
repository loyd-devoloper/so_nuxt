<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QadSchoolAccountController;
use App\Http\Controllers\API\QadSdoAccountController;
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
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::middleware(['auth:sanctum','qad'])->prefix('qad')->group(function () {
    Route::get('/userInfo', [AuthController::class, 'qadInfo']);
    ################################## SDO ACCOUNT ########################################################
    Route::prefix('sdo-account')->group(function () {
        Route::get('/', [\App\Http\Controllers\API\QadSdoAccountController::class, 'index']);
        Route::post('/store', [\App\Http\Controllers\API\QadSdoAccountController::class, 'store']);
        Route::get('/edit/{sdo_account_id}', [\App\Http\Controllers\API\QadSdoAccountController::class, 'edit']);
        Route::post('/update/{sdo_account_id}', [\App\Http\Controllers\API\QadSdoAccountController::class, 'update']);
    });
    ################################ SCHOOL ACCOUNT ##########################################################
    Route::prefix('school-account')->group(function () {
        Route::get('/', [QadSchoolAccountController::class, 'index']);
        Route::post('/store', [QadSchoolAccountController::class, 'store']);
        Route::get('/edit/{school_account_id}', [QadSchoolAccountController::class, 'show']);
        Route::post('/update/{school_account_id}', [QadSchoolAccountController::class, 'update']);
        Route::get('/sdo-list', [QadSchoolAccountController::class, 'sdoList']);
        Route::post('/store-bulk', [QadSchoolAccountController::class, 'storeBulk']);
    });
});

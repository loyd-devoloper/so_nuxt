<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QadCurriculumController;
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
    Route::post('/school/login', [\App\Http\Controllers\API\School\AuthController::class, 'schoolLogin']);
    Route::get('/otp-data/{verification_id}', [AuthController::class, 'getOtp']);
    Route::post('/otp-verification', [AuthController::class, 'verifyOtp']);
    Route::put('/otp-resend-verification', [AuthController::class, 'resendOtp']);
});
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);


Route::middleware(['auth:sanctum','qad'])->prefix('qad')->group(function () {
    Route::get('/userInfo', [AuthController::class, 'qadInfo']);
    ################################## SDO ACCOUNT ########################################################
    Route::controller(\App\Http\Controllers\API\QadSdoAccountController::class)->prefix('sdo-account')->group(function () {
        Route::get('/', 'index');
        Route::post('/store','store');
        Route::get('/edit/{sdo_account_id}','edit');
        Route::post('/update/{sdo_account_id}','update');
    });
    ################################ SCHOOL ACCOUNT ##########################################################
    Route::controller(\App\Http\Controllers\API\QadSchoolAccountController::class)->prefix('school-account')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/edit/{school_account_id}', 'show');
        Route::post('/update/{school_account_id}', 'update');
        Route::get('/sdo-list', 'sdoList');
        Route::post('/store-bulk', 'storeBulk');
    });
    ################################ CURRICULUM ##########################################################
    Route::controller(QadCurriculumController::class)->prefix('curriculum')->group(function () {
        Route::get('/', 'index');
        Route::post('/store','store');
        Route::get('/edit/{school_account_id}', 'show');
        Route::post('/update/{school_account_id}','update');
        Route::post('/store-program/{curriculum_id}', 'storeProgram');
        Route::get('/programs/{curriculum_id}', 'indexProgram');
        Route::get('/program/edit/{curriculum_id}/{program_id}', 'showProgram');
        Route::post('/program/update/{program_id}', 'updateProgram');
        Route::delete('/program/delete/{program_id}', 'destroyProgram');
    });
});
Route::middleware(['auth:sanctum','qad'])->prefix('school')->group(function () {
    Route::get('schoolInfo',[\App\Http\Controllers\API\School\AuthController::class,'schoolInfo']);
    Route::get('latest/curriculum',[\App\Http\Controllers\API\School\AuthController::class,'latestCurriculum']);
    Route::post('first-time-login',[\App\Http\Controllers\API\School\AuthController::class,'firstTimeLogin']);
});

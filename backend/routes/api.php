<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('login',[AuthController::class,'login'])->middleware(['guest']);
Route::middleware(['guest'])->post('/auth/qad/login', [AuthController::class, 'qadLogin']);

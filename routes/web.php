<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\DokterController;
use App\Http\Controllers\Master\PoliklinikController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/auth', action: [ Auth::class,'index']);

Route::post('/login', action: [AuthController::class, 'login']);
Route::post('/auth/check-username', action: [AuthController::class, 'checkUsername']);
Route::post('/check', action: [AuthController::class, 'check']);
Route::get('/auth/login-data', action: [AuthController::class, 'loginData']);


Route::post('/auth/test', action: [Auth::class, 'test']);

Route::middleware(['api_token'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
    });
    Route::get('/master/poliklinik', action: [PoliklinikController::class, 'index']);
    Route::post('/master/poliklinik', action: [PoliklinikController::class, 'store']);
    Route::get('/master/dokter', action: [DokterController::class, 'index']);
    Route::post('/master/dokter', action: [DokterController::class, 'store']);
});
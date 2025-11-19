<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Jkn\JknSuratkontrolController;
use App\Http\Controllers\Master\PasienController;
use App\Http\Controllers\Master\PoliklinikController;
use App\Http\Controllers\Registrasi\RegistrasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/auth', action: [ Auth::class,'index']);

Route::post('/login', action: [AuthController::class, 'login']);
Route::post('/auth/check-username', action: [AuthController::class, 'checkUsername']);
Route::post('/check', action: [AuthController::class, 'check']);
Route::get('/auth/check-username', action: [AuthController::class, 'test']);


Route::get('/master/poliklinik', action: [PoliklinikController::class, 'index']);
Route::post('/master/poliklinik', action: [PoliklinikController::class, 'store']);

Route::get('/master/pasien', action: [PasienController::class, 'index']);

Route::middleware(['api_token'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
    });

    Route::prefix('jkn')->group(function () {
        Route::controller(JknSuratkontrolController::class)->group(function () {
            Route::post('/surkon/getdata', 'getdata');
        });
    });

    Route::prefix('registrasi')->group(function () {
        Route::controller(RegistrasiController::class)->group(function () {
            Route::post('/post', 'post');
        });
    });
});

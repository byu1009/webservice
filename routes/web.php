<?php

use App\Http\Controllers\AuthController;
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

Route::middleware(['api_token'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
    });
});
<?php

use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// public
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/enterprise/register', [EnterpriseController::class, 'register']);

// User routes
Route::post('/user/login', [UserController::class, 'login']);
Route::middleware(['auth:sanctum'])->get('/user/me', [UserController::class, 'me']);
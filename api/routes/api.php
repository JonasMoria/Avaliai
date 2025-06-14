<?php

use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});


// Public User Routes
Route::prefix('/user')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

// Public Enterprise Routes
Route::prefix('/enterprise')->group(function() {
    Route::post('/register', [EnterpriseController::class, 'register']);
    Route::post('/login', [EnterpriseController::class, 'login']);
});

// Private Routes
Route::middleware(['auth:sanctum'])->group(function() {
    // User
    Route::prefix('/user')->group(function() {
        Route::get('/me', [UserController::class, 'me']);
    });

    // Enterprise
    Route::prefix('/enterprise')->group(function() {
        Route::get('/me', [EnterpriseController::class, 'me']);
    });
});

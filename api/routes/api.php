<?php

use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\EnterpriseServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Testing route
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// Searching Routes
Route::get('/search', [SearchController::class, 'search']);
Route::get('/search/all', [SearchController::class, 'searchAll']);
Route::get('/search/enterprise/{id}', [SearchController::class, 'getEnterpriseById']);
Route::get('/search/service/{id}', [SearchController::class, 'getServiceById']);

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
        Route::post('/logout', [UserController::class, 'logout']);

        Route::post('/account/update/photo', [UserController::class, 'updateProfilePhoto']);
        Route::post('/account/update/informations', [UserController::class, 'updateInformations']);
        Route::post('/account/update/email', [UserController::class, 'updateEmail']);
        Route::post('/account/update/password', [UserController::class, 'updatePassword']);
    });

    // Enterprise
    Route::prefix('/enterprise')->group(function() {
        Route::get('/me', [EnterpriseController::class, 'me']);
        Route::post('/logout', [EnterpriseController::class, 'logout']);

        Route::post('/services/register', [EnterpriseServiceController::class, 'register']);
        Route::get('/services/list', [EnterpriseServiceController::class, 'listMyServices']);
        Route::post('/services/update/{id}', [EnterpriseServiceController::class, 'update']);
        Route::delete('/services/delete/{id}', [EnterpriseServiceController::class, 'remove']);

        Route::post('/account/update/photo', [EnterpriseController::class, 'updateProfilePhoto']);
        Route::post('/account/update/informations', [EnterpriseController::class, 'updateInformations']);
        Route::post('/account/update/email', [EnterpriseController::class, 'updateEmail']);
        Route::post('/account/update/password', [EnterpriseController::class, 'updatePassword']);
    });
});

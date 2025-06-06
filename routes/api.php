<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllerAPI;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

// Authentication Routes (Public - No middleware)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthControllerAPI::class, 'login']);
});

// Protected Routes (Require authentication)
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('/logout', [AuthControllerAPI::class, 'logout']);
    Route::post('/logout-all', [AuthControllerAPI::class, 'logoutAll']);
    Route::get('/me', [AuthControllerAPI::class, 'me']);
});
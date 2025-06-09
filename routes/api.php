<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllerAPI;
use App\Http\Controllers\PembeliControllersAPI;
use App\Http\Controllers\TransaksiPenitipanControllersAPI;
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
Route::middleware('auth:sanctum')->group(function () {

    // Rute yang berhubungan dengan data pengguna yang login
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthControllerAPI::class, 'logout']);
        Route::post('/logout-all', [AuthControllerAPI::class, 'logoutAll']);
        Route::get('/me', [AuthControllerAPI::class, 'me']);
    });
    //PEMBELI
    Route::get('/pembeli/history', [App\Http\Controllers\PembeliControllersAPI::class, 'getHistoryTransaksi']);
     //PENITIP
     Route::get('/penitip/history', [TransaksiPenitipanControllersAPI::class, 'getHistoryForPenitip']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginAPIControllers;

// Test route untuk memastikan API berfungsi
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'version' => 'Laravel 11',
        'timestamp' => now()
    ]);
});

// Routes untuk Penitip Authentication
Route::prefix('penitip')->group(function () {
    
    // Login route (public)
    Route::post('/login', [loginAPIControllers::class, 'loginAPIControllers'])
         ->name('api.penitip.login');

    // Protected routes dengan Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [loginAPIControllers::class, 'apiLogoutPenitip'])
             ->name('api.penitip.logout');

        Route::get('/profile', [loginAPIControllers::class, 'apiProfilePenitip'])
             ->name('api.penitip.profile');
    });

    // JWT refresh token (jika menggunakan JWT)
    Route::post('/refresh-token', [loginAPIControllers::class, 'apiRefreshTokenPenitip'])
         ->name('api.penitip.refresh-token');
});

// Default user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});